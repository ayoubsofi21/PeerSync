<?php
require '../../config/Database.php';
require '../../repositories/HelpRequestRepository.php';

$pdo = Database::connect();

$repo = new HelpRequestRepository($pdo);
$requests = $repo->findAll();



// Total demandes
$total = count($requests);

// En attente
$pending = count(array_filter($requests, function($r) {
    return $r->status === 'PENDING';
}));

// Assignées
$assigned = count(array_filter($requests, function($r) {
    return $r->status === 'ASSIGNED';
}));

// Résolues
$resolved = count(array_filter($requests, function($r) {
    return $r->status === 'RESOLVED';
}));

// $requests = array_filter($requests, function($r) use ($status, $tech) {
//     if ($status !== 'all' && $r->status !== $status) return false;
//     if ($tech && $r->technology !== $tech) return false;
//     return true;
// });s

?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>PeerSync — ENAA</title>
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
<style>
  :root {
    --brand: #0f6e56;
    --brand-light: #e1f5ee;
    --brand-mid: #1d9e75;
    --brand-dark: #085041;
    --accent: #d85a30;
    --accent-light: #faece7;
    --amber: #ba7517;
    --amber-light: #faeeda;
    --purple: #534ab7;
    --purple-light: #eeedfe;
    --blue: #185fa5;
    --blue-light: #e6f1fb;
    --bg: #f8faf9;
    --surface: #ffffff;
    --border: #e2e8e5;
    --text: #1a2520;
    --muted: #6b7f76;
  }
  * { box-sizing: border-box; }
  body {
    font-family: 'DM Sans', sans-serif;
    background: var(--bg);
    color: var(--text);
    margin: 0;
    min-height: 100vh;
  }
  h1, h2, h3, .font-display { font-family: 'Syne', sans-serif; }
  .sidebar {
    width: 240px; min-height: 100vh; background: var(--brand-dark);
    position: fixed; left: 0; top: 0; z-index: 100;
    display: flex; flex-direction: column;
  }
  .main { margin-left: 240px; padding: 2rem; min-height: 100vh; }
  .nav-item {
    display: flex; align-items: center; gap: 10px;
    padding: 10px 20px; color: rgba(255,255,255,0.7);
    cursor: pointer; border-radius: 8px; margin: 2px 8px;
    font-size: 14px; font-weight: 500; transition: all .15s;
    text-decoration: none;
  }
  .nav-item:hover, .nav-item.active { background: rgba(255,255,255,.12); color: white; }
  .nav-item svg { flex-shrink: 0; }
  .badge {
    display: inline-flex; align-items: center; gap: 4px;
    padding: 3px 10px; border-radius: 20px; font-size: 12px; font-weight: 500;
  }
  .badge-pending { background: var(--amber-light); color: var(--amber); }
  .badge-assigned { background: var(--blue-light); color: var(--blue); }
  .badge-resolved { background: var(--brand-light); color: var(--brand); }
  .badge-tag { background: var(--purple-light); color: var(--purple); }
  .badge-role { background: var(--brand-light); color: var(--brand-dark); }
  .card {
    background: var(--surface); border: 1px solid var(--border);
    border-radius: 14px; padding: 1.5rem;
  }
  .stat-card {
    background: var(--surface); border: 1px solid var(--border);
    border-radius: 12px; padding: 1.25rem; text-align: center;
  }
  .btn-primary {
    background: var(--brand); color: white; border: none;
    padding: 9px 20px; border-radius: 8px; font-size: 14px; font-weight: 500;
    cursor: pointer; transition: background .15s; font-family: 'DM Sans', sans-serif;
  }
  .btn-primary:hover { background: var(--brand-dark); }
  .btn-outline {
    background: transparent; color: var(--brand); border: 1.5px solid var(--brand);
    padding: 7px 18px; border-radius: 8px; font-size: 13px; font-weight: 500;
    cursor: pointer; transition: all .15s; font-family: 'DM Sans', sans-serif;
  }
  .btn-outline:hover { background: var(--brand-light); }
  .btn-accent {
    background: var(--accent); color: white; border: none;
    padding: 8px 18px; border-radius: 8px; font-size: 13px; font-weight: 500;
    cursor: pointer; font-family: 'DM Sans', sans-serif; transition: opacity .15s;
  }
  .btn-accent:hover { opacity: .88; }
  .btn-sm {
    padding: 6px 14px; font-size: 12px; border-radius: 6px;
  }
  .input-field {
    width: 100%; border: 1px solid var(--border); border-radius: 8px;
    padding: 9px 12px; font-size: 14px; font-family: 'DM Sans', sans-serif;
    outline: none; background: white; color: var(--text);
    transition: border-color .15s;
  }
  .input-field:focus { border-color: var(--brand-mid); }
  select.input-field { cursor: pointer; }
  .tab-btn {
    padding: 8px 18px; border-radius: 8px; font-size: 14px; font-weight: 500;
    cursor: pointer; border: none; background: transparent; color: var(--muted);
    font-family: 'DM Sans', sans-serif; transition: all .15s;
  }
  .tab-btn.active { background: var(--brand-light); color: var(--brand-dark); }
  .modal-overlay {
    position: fixed; inset: 0; background: rgba(0,0,0,.45); z-index: 200;
    display: flex; align-items: center; justify-content: center; padding: 1rem;
  }
  .modal {
    background: white; border-radius: 16px; padding: 2rem;
    width: 100%; max-width: 520px; position: relative;
  }
  .avatar {
    width: 38px; height: 38px; border-radius: 50%; display: flex; align-items: center;
    justify-content: center; font-weight: 700; font-size: 14px; flex-shrink: 0;
    font-family: 'Syne', sans-serif;
  }
  .stars { color: #f59e0b; font-size: 18px; cursor: pointer; }
  .star-empty { color: #d1d5db; }
  .progress-bar {
    height: 6px; border-radius: 3px; background: var(--border); overflow: hidden;
  }
  .progress-fill { height: 100%; background: var(--brand-mid); border-radius: 3px; }
  .leaderboard-rank {
    width: 28px; height: 28px; border-radius: 50%; display: flex;
    align-items: center; justify-content: center; font-weight: 700;
    font-size: 12px; font-family: 'Syne', sans-serif;
  }
  .hero-section {
    background: linear-gradient(135deg, var(--brand-dark) 0%, var(--brand) 100%);
    border-radius: 16px; padding: 2rem 2.5rem; color: white; margin-bottom: 2rem;
    position: relative; overflow: hidden;
  }
  .hero-section::after {
    content: 'PS'; font-family: 'Syne', sans-serif; font-size: 160px; font-weight: 800;
    position: absolute; right: -20px; top: -30px; opacity: .07; color: white;
    line-height: 1;
  }
  .section { display: none; }
  .section.active { display: block; }
  .notification-dot {
    width: 8px; height: 8px; background: var(--accent); border-radius: 50%;
    position: absolute; top: 6px; right: 8px;
  }
  .tag-skill {
    display: inline-flex; align-items: center; gap: 4px;
    background: var(--purple-light); color: var(--purple); border-radius: 20px;
    padding: 3px 10px; font-size: 12px; font-weight: 500; cursor: pointer;
    transition: background .12s;
  }
  .tag-skill:hover { background: #cecbf6; }
</style>
</head>
<body>

<!-- SIDEBAR -->
<aside class="sidebar">
  <div style="padding: 1.5rem 1.5rem 1rem;">
    <div style="display:flex; align-items:center; gap:10px;">
      <div style="width:34px;height:34px;background:var(--brand-mid);border-radius:8px;display:flex;align-items:center;justify-content:center;">
        <svg width="18" height="18" fill="none" stroke="white" stroke-width="2.2" stroke-linecap="round"><path d="M9 2a4 4 0 100 8 4 4 0 000-8zM3 16c0-3 2.7-5 6-5s6 2 6 5"/></svg>
      </div>
      <div>
        <div style="font-family:'Syne',sans-serif;font-size:16px;font-weight:700;color:white;">PeerSync</div>
        <div style="font-size:11px;color:rgba(255,255,255,.5);">ENAA</div>
      </div>
    </div>
  </div>

  <!-- User pill -->
  <div style="margin:0 8px 1rem;background:rgba(255,255,255,.1);border-radius:10px;padding:10px 12px;display:flex;align-items:center;gap:10px;">
    <div class="avatar" style="background:#9fe1cb;color:#085041;width:32px;height:32px;font-size:12px;">YO</div>
    <div style="flex:1;min-width:0;">
      <div style="color:white;font-size:13px;font-weight:500;">Youssef O.</div>
      <div style="color:rgba(255,255,255,.5);font-size:11px;">Apprenant</div>
    </div>
  </div>

  <nav style="flex:1;">
    <div style="padding:0 8px 4px 20px;font-size:10px;color:rgba(255,255,255,.35);font-weight:600;letter-spacing:.08em;text-transform:uppercase;margin-bottom:4px;">Menu</div>
    <a class="nav-item active" onclick="showSection('dashboard')" id="nav-dashboard">
      <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><rect x="2" y="2" width="5" height="5" rx="1"/><rect x="9" y="2" width="5" height="5" rx="1"/><rect x="2" y="9" width="5" height="5" rx="1"/><rect x="9" y="9" width="5" height="5" rx="1"/></svg>
      Tableau de bord
    </a>
    <a class="nav-item" onclick="showSection('requests')" id="nav-requests" style="position:relative;">
      <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z" transform="scale(0.73)"/></svg>
      Demandes d'aide
      <div class="notification-dot"></div>
    </a>
    <a class="nav-item" onclick="showSection('tutor')" id="nav-tutor">
      <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="8" cy="5" r="3"/><path d="M2 14c0-3.3 2.7-6 6-6s6 2.7 6 6"/><path d="M11 2l1.5 1.5L15 1"/></svg>
      Espace Tuteur
    </a>
    <a class="nav-item" onclick="showSection('profile')" id="nav-profile">
      <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="8" cy="5" r="3"/><path d="M2 14c0-3.3 2.7-6 6-6s6 2.7 6 6"/></svg>
      Mon Profil
    </a>
    <a class="nav-item" onclick="showSection('leaderboard')" id="nav-leaderboard">
      <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M8 2v12M4 6v8M12 4v10"/></svg>
      Mur des Héros
    </a>
    <a class="nav-item" onclick="showSection('admin')" id="nav-admin">
      <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M2 14l2-6 4 3 4-8 2 5"/></svg>
      Admin
    </a>
    
  </nav>

  <div style="padding:1rem 1.5rem;border-top:1px solid rgba(255,255,255,.1);font-size:12px;color:rgba(255,255,255,.35);">
    ENAA Bootcamp © 2026
  </div>
</aside>

<!-- MAIN CONTENT -->
<main class="main">

  <!-- ====== DASHBOARD ====== -->
  <section id="section-dashboard" class="section active">
    <div class="hero-section">
      <div style="font-size:12px;opacity:.6;margin-bottom:.4rem;">Bienvenue sur</div>
      <h1 style="margin:0 0 .4rem;font-size:2rem;font-weight:800;">PeerSync</h1>
      <p style="margin:0;opacity:.75;font-size:14px;max-width:400px;">La plateforme d'entraide de l'ENAA — trouvez ou devenez un tuteur, suivez vos sessions et valorisez votre engagement.</p>
      <div style="margin-top:1.5rem;display:flex;gap:10px;flex-wrap:wrap;">
        <button class="btn-primary" style="background:white;color:var(--brand-dark);font-weight:600;" onclick="openNewRequestModal()">+ Nouvelle demande</button>
        <button class="btn-outline" style="border-color:rgba(255,255,255,.5);color:white;" onclick="showSection('tutor')">Devenir tuteur →</button>
      </div>
    </div>

    <!-- Stats -->
  <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:2rem;">

  <div class="stat-card">
        <div style="font-size:28px;font-weight:800;font-family:'Syne',sans-serif;color:var(--brand);">
          <?= $total ?>
        </div>
        <div style="font-size:12px;color:var(--muted);margin-top:4px;">
          Total demandes
        </div>
      </div>

      <div class="stat-card">
        <div style="font-size:28px;font-weight:800;font-family:'Syne',sans-serif;color:var(--amber);">
          <?= $pending ?>
        </div>
        <div style="font-size:12px;color:var(--muted);margin-top:4px;">
          En attente
        </div>
      </div>

      <div class="stat-card">
        <div style="font-size:28px;font-weight:800;font-family:'Syne',sans-serif;color:var(--blue);">
          <?= $assigned ?>
        </div>
        <div style="font-size:12px;color:var(--muted);margin-top:4px;">
          Assignées
        </div>
      </div>

      <div class="stat-card">
        <div style="font-size:28px;font-weight:800;font-family:'Syne',sans-serif;color:var(--brand-mid);">
          <?= $resolved ?>
        </div>
        <div style="font-size:12px;color:var(--muted);margin-top:4px;">
          Résolues
        </div>
      </div>

    </div>

    <div style="display:grid;grid-template-columns:1fr 340px;gap:1.5rem;">
      <!-- Recent requests -->
      <div class="card">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.25rem;">
          <h2 style="margin:0;font-size:15px;font-weight:700;">Demandes récentes</h2>
          <button class="btn-outline btn-sm" onclick="showSection('requests')">Voir tout</button>
        </div>
        <!-- display here all request -->
        <div id="dashboard-requests-list" style="display:flex;flex-direction:column;gap:12px;">
            <?php foreach ($requests as $r): ?>
                <div class="card">
                <h3><?= htmlspecialchars($r->title) ?></h3>
                <p><?= htmlspecialchars($r->description) ?></p>
                <span class="badge badge-tag"><?= htmlspecialchars($r->technology) ?></span>
                <span class="badge"><?= htmlspecialchars($r->status) ?></span>
                </div>
            <?php endforeach; ?>
        </div>
      </div>
      <!-- Tech stats -->
      <div class="card">
        <h2 style="margin:0 0 1.25rem;font-size:15px;font-weight:700;">Technos les plus demandées</h2>
        <div id="tech-stats"></div>
      </div>
    </div>
  </section>

  <!-- ====== DEMANDES ====== -->
  <section id="section-requests" class="section">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem;">
      <div>
        <h1 style="margin:0 0 4px;font-size:22px;">Demandes d'aide</h1>
        <p style="margin:0;color:var(--muted);font-size:14px;">Tableau de bord des tickets PeerSync</p>
      </div>
      <button class="btn-primary" onclick="openNewRequestModal()">+ Nouvelle demande</button>
    </div>
    <!-- Filters -->
    <div style="display:flex;gap:8px;margin-bottom:1.5rem;flex-wrap:wrap;">
      <button class="tab-btn active" onclick="filterRequests('all', this)">Toutes</button>
      <button class="tab-btn" onclick="filterRequests('PENDING', this)">En attente</button>
      <button class="tab-btn" onclick="filterRequests('ASSIGNED', this)">Assignées</button>
      <button class="tab-btn" onclick="filterRequests('RESOLVED', this)">Résolues</button>
      <div style="margin-left:auto;">
        <select class="input-field" style="width:160px;padding:7px 12px;font-size:13px;" onchange="filterByTech(this.value)">
          <option value="">Toutes les technos</option>
          <option>PHP / POO</option>
          <option>SQL</option>
          <option>JavaScript</option>
          <option>HTML / CSS</option>
          <option>Git</option>
        </select>
      </div>
    </div>
    <div id="requests-list" style="display:flex;flex-direction:column;gap:12px;"></div>
  </section>

  <!-- ====== ESPACE TUTEUR ====== -->
  <section id="section-tutor" class="section">
    <div style="margin-bottom:1.5rem;">
      <h1 style="margin:0 0 4px;font-size:22px;">Espace Tuteur</h1>
      <p style="margin:0;color:var(--muted);font-size:14px;">Prenez en charge des demandes et cumulez des points</p>
    </div>
    <!-- Tuteur stats -->
    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:14px;margin-bottom:1.5rem;">
      <div class="stat-card" style="border-left:3px solid var(--brand);">
        <div style="font-size:30px;font-weight:800;font-family:'Syne',sans-serif;color:var(--brand);">320</div>
        <div style="font-size:12px;color:var(--muted);margin-top:4px;">Points accumulés</div>
      </div>
      <div class="stat-card" style="border-left:3px solid var(--purple);">
        <div style="font-size:30px;font-weight:800;font-family:'Syne',sans-serif;color:var(--purple);">8</div>
        <div style="font-size:12px;color:var(--muted);margin-top:4px;">Sessions complétées</div>
      </div>
      <div class="stat-card" style="border-left:3px solid var(--amber);">
        <div style="font-size:30px;font-weight:800;font-family:'Syne',sans-serif;color:var(--amber);">3</div>
        <div style="font-size:12px;color:var(--muted);margin-top:4px;">Badges obtenus</div>
      </div>
    </div>
    <div style="display:grid;grid-template-columns:1fr 300px;gap:1.5rem;">
      <!-- Pending to take -->
      <div class="card">
        <h2 style="margin:0 0 1.25rem;font-size:15px;font-weight:700;">Demandes disponibles</h2>
        <!-- <div id="tutor-requests-list"></div> -->
       
         <?php  
            $pendingRequests = array_filter($requests, function($r) {
                return $r->status === 'PENDING';
            });
            ?>

            <div id="tutor-requests-list">

            <?php if (!empty($pendingRequests)): ?>

                <?php foreach ($pendingRequests as $r): ?>
                    <div class="card" style="margin-bottom:10px;">

                        <div style="display:flex;justify-content:space-between;align-items:center;">
                            <span class="badge badge-tag">
                             <?= htmlspecialchars($r->technology) ?>
                            </span>
                            <span class="badge badge-pending">En attente</span>
                        </div>

                        <h3 style="margin:10px 0 5px;font-size:15px;">
                          <?= htmlspecialchars($r->title) ?>
                        </h3>

                        <p style="margin:0 0 10px;color:var(--muted);font-size:13px;">
                            <?= htmlspecialchars($r->description) ?>
                        </p>

                        <div style="font-size:12px;color:var(--muted);margin-bottom:10px;">
                            Par <strong><?= htmlspecialchars($r->author) ?></strong>
                        </div>

                        <form action="../../actions/assign-request.php" method="POST">
                            <input type="hidden" name="request_id" value="<?= $r->id ?>">
                            <button class="btn-primary btn-sm" type="submit">
                                Aider cet apprenant
                            </button>
                        </form>

                    </div>
                <?php endforeach; ?>

            <?php else: ?>

                <div style="text-align:center;padding:2rem;color:var(--muted);">
                    Aucune demande en attente 🎉
                </div>

            <?php endif; ?>

            </div>
      </div>
      <!-- Badges -->
      <div class="card">
        <h2 style="margin:0 0 1.25rem;font-size:15px;font-weight:700;">Mes Badges</h2>
        <div id="badges-list"></div>
      </div>
    </div>
  </section>

  <!-- ====== PROFIL ====== -->
  <section id="section-profile" class="section">
    <div style="margin-bottom:1.5rem;">
      <h1 style="margin:0 0 4px;font-size:22px;">Mon Profil</h1>
      <p style="margin:0;color:var(--muted);font-size:14px;">Compétences maîtrisées et à travailler</p>
    </div>
    <div style="display:grid;grid-template-columns:300px 1fr;gap:1.5rem;">
      <div class="card" style="text-align:center;">
        <div class="avatar" style="background:var(--brand-light);color:var(--brand-dark);width:72px;height:72px;font-size:24px;margin:0 auto 1rem;">YO</div>
        <h2 style="margin:0 0 4px;font-size:18px;">Youssef Ouali</h2>
        <span class="badge badge-role">Apprenant</span>
        <div style="margin-top:1rem;font-size:13px;color:var(--muted);">Promotion Web 2025/2026</div>
        <div style="margin-top:1.25rem;border-top:1px solid var(--border);padding-top:1.25rem;">
          <div style="display:flex;justify-content:space-between;font-size:13px;margin-bottom:8px;">
            <span style="color:var(--muted);">Sessions en tant qu'apprenant</span><span style="font-weight:600;">5</span>
          </div>
          <div style="display:flex;justify-content:space-between;font-size:13px;">
            <span style="color:var(--muted);">Sessions en tant que tuteur</span><span style="font-weight:600;">8</span>
          </div>
        </div>
      </div>
      <div style="display:flex;flex-direction:column;gap:1.5rem;">
        <div class="card">
          <h3 style="margin:0 0 1rem;font-size:14px;font-weight:700;color:var(--brand-dark);">✅ Compétences maîtrisées</h3>
          <div style="display:flex;flex-wrap:wrap;gap:8px;" id="skills-mastered">
            <span class="tag-skill">PHP / POO</span>
            <span class="tag-skill">SQL</span>
            <span class="tag-skill">Git</span>
            <span class="tag-skill">HTML / CSS</span>
          </div>
          <div style="margin-top:1rem;display:flex;gap:8px;">
            <input class="input-field" id="new-skill-input" placeholder="Ajouter une compétence…" style="flex:1;" onkeydown="if(event.key==='Enter') addSkill('mastered')">
            <button class="btn-outline btn-sm" onclick="addSkill('mastered')">+</button>
          </div>
        </div>
        <div class="card">
          <h3 style="margin:0 0 1rem;font-size:14px;font-weight:700;color:var(--accent);">📚 À travailler</h3>
          <div style="display:flex;flex-wrap:wrap;gap:8px;" id="skills-learning">
            <span class="tag-skill" style="background:#faeeda;color:#854f0b;">JavaScript ES6+</span>
            <span class="tag-skill" style="background:#faeeda;color:#854f0b;">React</span>
            <span class="tag-skill" style="background:#faeeda;color:#854f0b;">API REST</span>
          </div>
          <div style="margin-top:1rem;display:flex;gap:8px;">
            <input class="input-field" id="new-learning-input" placeholder="Ajouter à travailler…" style="flex:1;" onkeydown="if(event.key==='Enter') addSkill('learning')">
            <button class="btn-outline btn-sm" style="border-color:var(--amber);color:var(--amber);" onclick="addSkill('learning')">+</button>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ====== LEADERBOARD ====== -->
  <section id="section-leaderboard" class="section">
    <div style="margin-bottom:1.5rem;">
      <h1 style="margin:0 0 4px;font-size:22px;">🏆 Mur des Héros</h1>
      <p style="margin:0;color:var(--muted);font-size:14px;">Classement des tuteurs les plus actifs du mois</p>
    </div>
    <div style="display:grid;grid-template-columns:1fr 320px;gap:1.5rem;">
      <div class="card">
        <div id="leaderboard-list"></div>
      </div>
      <div class="card">
        <h3 style="margin:0 0 1.25rem;font-size:14px;font-weight:700;">Podium du mois</h3>
        <div id="podium"></div>
      </div>
    </div>
  </section>

  <!-- ====== ADMIN ====== -->
  <section id="section-admin" class="section">
    <div style="margin-bottom:1.5rem;">
      <h1 style="margin:0 0 4px;font-size:22px;">Administration</h1>
      <p style="margin:0;color:var(--muted);font-size:14px;">Tableau de bord statistique — ENAA</p>
    </div>
    <!-- Big stats -->
    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:1.5rem;">
      <div class="stat-card" style="border-top:3px solid var(--brand);">
        <div style="font-size:32px;font-weight:800;font-family:'Syne',sans-serif;color:var(--brand);">48</div>
        <div style="font-size:12px;color:var(--muted);margin-top:4px;">Sessions totales</div>
      </div>
      <div class="stat-card" style="border-top:3px solid var(--purple);">
        <div style="font-size:32px;font-weight:800;font-family:'Syne',sans-serif;color:var(--purple);">12</div>
        <div style="font-size:12px;color:var(--muted);margin-top:4px;">Tuteurs actifs</div>
      </div>
      <div class="stat-card" style="border-top:3px solid var(--amber);">
        <div style="font-size:32px;font-weight:800;font-family:'Syne',sans-serif;color:var(--amber);">96h</div>
        <div style="font-size:12px;color:var(--muted);margin-top:4px;">Heures d'entraide</div>
      </div>
      <div class="stat-card" style="border-top:3px solid var(--accent);">
        <div style="font-size:32px;font-weight:800;font-family:'Syne',sans-serif;color:var(--accent);">94%</div>
        <div style="font-size:12px;color:var(--muted);margin-top:4px;">Taux de résolution</div>
      </div>
    </div>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;">
      <div class="card">
        <h3 style="margin:0 0 1.25rem;font-size:14px;font-weight:700;">Top 3 Tuteurs de la semaine</h3>
        <div id="admin-top-tutors"></div>
      </div>
      <div class="card">
        <h3 style="margin:0 0 1.25rem;font-size:14px;font-weight:700;">Volume par technologie</h3>
        <div id="admin-tech-chart"></div>
      </div>
    </div>
  </section>

</main>

<!-- ====== MODAL: Nouvelle demande ====== -->
<div id="modal-new-request" style="display:none;" class="modal-overlay"
     onclick="if(event.target===this)closeModal()">

  <div class="modal">

    <form action="../../actions/create-request.php" method="POST">

      <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem;">
        <h2 style="margin:0;font-size:18px;">Nouvelle demande d'aide</h2>
        <button type="button" onclick="closeModal()"
                style="background:none;border:none;cursor:pointer;font-size:22px;color:var(--muted);">
          ×
        </button>
      </div>

      <div style="display:flex;flex-direction:column;gap:12px;">

        <div>
          <label style="font-size:13px;font-weight:500;display:block;margin-bottom:5px;">Sujet *</label>
          <input class="input-field" name="title" id="req-title"
                 placeholder="Ex: Bloqué sur l'héritage en POO" required>
        </div>

        <div>
          <label style="font-size:13px;font-weight:500;display:block;margin-bottom:5px;">Technologie *</label>
          <select class="input-field" name="technology" id="req-tech" required>
            <option value="">— Choisir —</option>
            <option value="PHP / POO">PHP / POO</option>
            <option value="SQL">SQL</option>
            <option value="JavaScript">JavaScript</option>
            <option value="HTML / CSS">HTML / CSS</option>
            <option value="Git">Git</option>
            <option value="Autre">Autre</option>
          </select>
        </div>

        <div>
          <label style="font-size:13px;font-weight:500;display:block;margin-bottom:5px;">Description *</label>
          <textarea class="input-field" name="description" id="req-desc"
                    rows="4" placeholder="Décrivez votre problème en détail…" required></textarea>
        </div>

        <!-- IMPORTANT: student id -->
        <input type="hidden" name="student_id" value="1">

      </div>

      <div style="display:flex;justify-content:flex-end;gap:10px;margin-top:1.5rem;">
        <button type="button" class="btn-outline" onclick="closeModal()">Annuler</button>
        <button type="submit" class="btn-primary">Publier la demande</button>
      </div>

    </form>

  </div>
</div>

<!-- ====== MODAL: Rate session ====== -->
<div id="modal-rate" style="display:none;" class="modal-overlay" onclick="if(event.target===this)closeModal()">
  <div class="modal" style="max-width:400px;text-align:center;">
    <h2 style="margin:0 0 .5rem;font-size:18px;">Évaluer la session</h2>
    <p style="color:var(--muted);font-size:14px;margin:0 0 1.5rem;" id="rate-subtitle">Donnez une note à votre tuteur</p>
    <div style="font-size:36px;margin-bottom:1.25rem;" id="star-rating">
      <span class="stars" onclick="setRating(1)" id="s1">★</span>
      <span class="stars star-empty" onclick="setRating(2)" id="s2">★</span>
      <span class="stars star-empty" onclick="setRating(3)" id="s3">★</span>
      <span class="stars star-empty" onclick="setRating(4)" id="s4">★</span>
      <span class="stars star-empty" onclick="setRating(5)" id="s5">★</span>
    </div>
    <textarea class="input-field" id="rate-comment" rows="3" placeholder="Commentaire (optionnel)…" style="resize:none;margin-bottom:1rem;"></textarea>
    <button class="btn-primary" style="width:100%;" onclick="submitRating()">Valider</button>
  </div>
</div>

<!-- ====== MODAL: Success ====== -->
<div id="modal-success" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.4);z-index:300;display:none;align-items:center;justify-content:center;">
  <div style="background:white;border-radius:16px;padding:2rem;text-align:center;max-width:320px;">
    <div style="font-size:48px;margin-bottom:.75rem;">✅</div>
    <h3 style="margin:0 0 .5rem;font-family:'Syne',sans-serif;" id="success-title">Succès !</h3>
    <p style="color:var(--muted);font-size:14px;margin:0 0 1.25rem;" id="success-msg"></p>
    <button class="btn-primary" onclick="document.getElementById('modal-success').style.display='none'">Fermer</button>
  </div>
</div>

<script>
// ============================================
// DATA
// ============================================
let currentRating = 1;
let activeRatingId = null;

const tutors = [
  { id: 1, name: "Karim Benali", initials: "KB", color: "#e1f5ee", textColor: "#085041", points: 520, sessions: 13, badges: ["Expert PHP", "Sauveur de la Semaine"], rating: 4.9 },
  { id: 2, name: "Salma Tahir", initials: "ST", color: "#eeedfe", textColor: "#3c3489", points: 410, sessions: 10, badges: ["Expert SQL"], rating: 4.7 },
  { id: 3, name: "Mehdi Rouai", initials: "MR", color: "#faece7", textColor: "#712b13", points: 360, sessions: 9, badges: ["Expert JS"], rating: 4.8 },
  { id: 4, name: "Nadia Chaoui", initials: "NC", color: "#e6f1fb", textColor: "#0c447c", points: 280, sessions: 7, badges: [], rating: 4.5 },
  { id: 5, name: "Omar Idrissi", initials: "OI", color: "#faeeda", textColor: "#633806", points: 200, sessions: 5, badges: [], rating: 4.3 },
];



const techData = [
  { name: "PHP / POO", count: 18, color: "#534ab7" },
  { name: "JavaScript", count: 12, color: "#185fa5" },
  { name: "SQL", count: 9, color: "#0f6e56" },
  { name: "HTML / CSS", count: 6, color: "#d85a30" },
  { name: "Git", count: 3, color: "#ba7517" },
];

const badges = [
  { icon: "🏅", name: "Expert PHP", desc: "5 sessions PHP validées", earned: true },
  { icon: "⚡", name: "Sauveur de la Semaine", desc: "3 sessions en une semaine", earned: true },
  { icon: "🌟", name: "Guide SQL", desc: "5 sessions SQL validées", earned: true },
  { icon: "🔒", name: "Expert JS", desc: "5 sessions JS — 2/5", earned: false, progress: 40 },
  { icon: "🚀", name: "Super Tuteur", desc: "20 sessions total — 8/20", earned: false, progress: 40 },
];
// ============================================
// NAVIGATION
// ============================================
function showSection(id) {
  document.querySelectorAll('.section').forEach(s => s.classList.remove('active'));
  document.querySelectorAll('.nav-item').forEach(n => n.classList.remove('active'));
  document.getElementById('section-' + id).classList.add('active');
  document.getElementById('nav-' + id).classList.add('active');
}

// ============================================
// RENDER
// ============================================
function statusBadge(status) {
  const map = { PENDING: ['badge-pending','En attente'], ASSIGNED: ['badge-assigned','Assignée'], RESOLVED: ['badge-resolved','Résolue'] };
  const [cls, label] = map[status] || ['badge-pending', status];
  return `<span class="badge ${cls}">${label}</span>`;
}

function renderRequestCard(r, context = 'list') {
  const isTutor = context === 'tutor';
  return `
    <div class="card" style="margin-bottom:0;border-radius:10px;transition:border-color .15s;" onmouseover="this.style.borderColor='var(--brand-mid)'" onmouseout="this.style.borderColor='var(--border)'">
      <div style="display:flex;align-items:flex-start;gap:12px;">
        <div style="flex:1;min-width:0;">
          <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;margin-bottom:6px;">
            ${statusBadge(r.status)}
            <span class="badge badge-tag">${r.technology}</span>
            <span style="font-size:12px;color:var(--muted);margin-left:auto;">${r.date}</span>
          </div>
          <h3 style="margin:0 0 4px;font-size:15px;font-weight:600;">${r.title}</h3>
          <p style="margin:0 0 8px;font-size:13px;color:var(--muted);">${r.description}</p>
          <div style="font-size:12px;color:var(--muted);">Par <strong>${r.author}</strong>${r.tutor ? ` · Tuteur : <strong>${r.tutor.name}</strong>` : ''}</div>
        </div>
      </div>
      <div style="display:flex;gap:8px;margin-top:12px;flex-wrap:wrap;">
        ${r.status === 'PENDING' && isTutor ? `<button class="btn-primary btn-sm" onclick="assignRequest(${r.id})">Aider cet apprenant</button>` : ''}
        ${r.status === 'ASSIGNED' && !isTutor ? `<button class="btn-accent btn-sm" onclick="resolveRequest(${r.id})">Marquer comme Résolu</button>` : ''}
        ${r.status === 'RESOLVED' && !r.rating ? `<button class="btn-outline btn-sm" onclick="openRateModal(${r.id})">⭐ Évaluer</button>` : ''}
        ${r.status === 'RESOLVED' && r.rating ? `<span style="font-size:13px;color:var(--muted);">${'★'.repeat(r.rating)}${'☆'.repeat(5-r.rating)}</span>` : ''}
      </div>
    </div>`;
}

function renderDashboard() {
  const recent = requests.slice(0,4);
  document.getElementById('dashboard-requests-list').innerHTML = recent.map(r => renderRequestCard(r, 'list')).join('<div style="height:10px"></div>');
  const max = techData[0].count;
  document.getElementById('tech-stats').innerHTML = techData.map(t => `
    <div style="margin-bottom:14px;">
      <div style="display:flex;justify-content:space-between;font-size:13px;margin-bottom:4px;">
        <span style="font-weight:500;">${t.name}</span>
        <span style="color:var(--muted);">${t.count} demandes</span>
      </div>
      <div class="progress-bar"><div class="progress-fill" style="width:${Math.round(t.count/max*100)}%;background:${t.color};"></div></div>
    </div>`).join('');
}

function renderRequests(filter = 'all', techFilter = '') {
  let list = requests;
  if (filter !== 'all') list = list.filter(r => r.status === filter);
  if (techFilter) list = list.filter(r => r.tech === techFilter);
  const el = document.getElementById('requests-list');
  if (!list.length) { el.innerHTML = `<div style="text-align:center;padding:3rem;color:var(--muted);">Aucune demande trouvée</div>`; return; }
  el.innerHTML = list.map(r => renderRequestCard(r, 'list')).join('');
}

// function renderTutorSection() {
//   const pending = requests.filter(r => r.status === 'PENDING');

//   document.getElementById('tutor-requests-list').innerHTML =
//     pending.length
//       ? pending.map(r => `
//           <div class="card" style="margin-bottom:10px;">
//             <div style="display:flex;justify-content:space-between;align-items:center;">
//               <span class="badge badge-tag">${r.tech}</span>
//               <span class="badge badge-pending">En attente</span>
//             </div>

//             <h3 style="margin:10px 0 5px;font-size:15px;">
//               ${r.title}
//             </h3>

//             <p style="margin:0 0 10px;color:var(--muted);font-size:13px;">
//               ${r.desc}
//             </p>

//             <div style="font-size:12px;color:var(--muted);margin-bottom:10px;">
//               Par <strong>${r.author}</strong>
//             </div>

//             <button class="btn-primary btn-sm" onclick="assignRequest(${r.id})">
//               Aider cet apprenant
//             </button>
//           </div>
//         `).join('')
//       : `<div style="text-align:center;padding:2rem;color:var(--muted);">
//           Aucune demande en attente 🎉
//         </div>`;
// }

function renderLeaderboard() {
  const sorted = [...tutors].sort((a,b) => b.points - a.points);
  const rankColors = ['#f59e0b','#9ca3af','#b45309','#6b7280','#6b7280'];
  document.getElementById('leaderboard-list').innerHTML = sorted.map((t,i) => `
    <div style="display:flex;align-items:center;gap:14px;padding:14px 0;border-bottom:1px solid var(--border);">
      <div class="leaderboard-rank" style="background:${i===0?'#fef3c7':i===1?'#f1f5f9':i===2?'#fef9c3':'var(--bg)'};color:${rankColors[i]};">${i+1}</div>
      <div class="avatar" style="background:${t.color};color:${t.textColor};">${t.initials}</div>
      <div style="flex:1;">
        <div style="font-size:14px;font-weight:600;">${t.name}</div>
        <div style="font-size:12px;color:var(--muted);">${t.sessions} sessions · Note: ${t.rating}/5</div>
        <div style="display:flex;gap:4px;margin-top:4px;flex-wrap:wrap;">${t.badges.map(b=>`<span class="badge badge-tag" style="font-size:11px;padding:2px 8px;">${b}</span>`).join('')}</div>
      </div>
      <div style="text-align:right;">
        <div style="font-size:18px;font-weight:800;font-family:'Syne',sans-serif;color:var(--brand);">${t.points}</div>
        <div style="font-size:11px;color:var(--muted);">pts</div>
      </div>
    </div>`).join('');
  const top3 = sorted.slice(0,3);
  document.getElementById('podium').innerHTML = `
    <div style="display:flex;align-items:flex-end;justify-content:center;gap:10px;margin-bottom:1rem;">
      <div style="text-align:center;">
        <div class="avatar" style="background:${top3[1].color};color:${top3[1].textColor};margin:0 auto 4px;">${top3[1].initials}</div>
        <div style="font-size:11px;font-weight:600;">${top3[1].name.split(' ')[0]}</div>
        <div style="background:#f1f5f9;border-radius:6px 6px 0 0;height:60px;margin-top:6px;display:flex;align-items:center;justify-content:center;font-family:'Syne',sans-serif;font-weight:700;color:#6b7280;">2</div>
      </div>
      <div style="text-align:center;">
        <div style="font-size:20px;margin-bottom:2px;">🏆</div>
        <div class="avatar" style="background:${top3[0].color};color:${top3[0].textColor};margin:0 auto 4px;">${top3[0].initials}</div>
        <div style="font-size:11px;font-weight:600;">${top3[0].name.split(' ')[0]}</div>
        <div style="background:#fef3c7;border-radius:6px 6px 0 0;height:90px;margin-top:6px;display:flex;align-items:center;justify-content:center;font-family:'Syne',sans-serif;font-weight:800;color:#d97706;font-size:18px;">1</div>
      </div>
      <div style="text-align:center;">
        <div class="avatar" style="background:${top3[2].color};color:${top3[2].textColor};margin:0 auto 4px;">${top3[2].initials}</div>
        <div style="font-size:11px;font-weight:600;">${top3[2].name.split(' ')[0]}</div>
        <div style="background:#fef9c3;border-radius:6px 6px 0 0;height:45px;margin-top:6px;display:flex;align-items:center;justify-content:center;font-family:'Syne',sans-serif;font-weight:700;color:#b45309;">3</div>
      </div>
    </div>
    <div style="font-size:12px;color:var(--muted);text-align:center;">Classement du mois de Mai 2026</div>`;
}

function renderAdmin() {
  const top3 = [...tutors].sort((a,b)=>b.points-a.points).slice(0,3);
  document.getElementById('admin-top-tutors').innerHTML = top3.map((t,i) => `
    <div style="display:flex;align-items:center;gap:12px;padding:10px 0;border-bottom:1px solid var(--border);">
      <div class="leaderboard-rank" style="background:${['#fef3c7','#f1f5f9','#fef9c3'][i]};color:${['#d97706','#6b7280','#b45309'][i]};">${i+1}</div>
      <div class="avatar" style="background:${t.color};color:${t.textColor};">${t.initials}</div>
      <div style="flex:1;">
        <div style="font-size:14px;font-weight:600;">${t.name}</div>
        <div style="font-size:12px;color:var(--muted);">${t.sessions} sessions cette semaine</div>
      </div>
      <strong style="color:var(--brand);">${t.points} pts</strong>
    </div>`).join('');
  const max = techData[0].count;
  document.getElementById('admin-tech-chart').innerHTML = techData.map(t => `
    <div style="margin-bottom:12px;">
      <div style="display:flex;justify-content:space-between;font-size:13px;margin-bottom:4px;">
        <span>${t.name}</span><span style="color:var(--muted);">${t.count}</span>
      </div>
      <div class="progress-bar"><div class="progress-fill" style="width:${Math.round(t.count/max*100)}%;background:${t.color};"></div></div>
    </div>`).join('');
}


// ============================================
// ACTIONS
// ============================================
let activeFilter = 'all';
let activeTech = '';

function filterRequests(filter, btn) {
  activeFilter = filter;
  document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
  btn.classList.add('active');
  renderRequests(activeFilter, activeTech);
}

function filterByTech(val) {
  activeTech = val;
  renderRequests(activeFilter, activeTech);
}

function openNewRequestModal() {
  document.getElementById('modal-new-request').style.display = 'flex';
}

function closeModal() {
  document.getElementById('modal-new-request').style.display = 'none';
  document.getElementById('modal-rate').style.display = 'none';
}

function submitRequest() {
  const title = document.getElementById('req-title').value.trim();
  const tech = document.getElementById('req-technology').value;
  const desc = document.getElementById('req-description').value.trim();
  if (!title || !tech || !desc) { alert('Veuillez remplir tous les champs.'); return; }
  requests.unshift({ id: Date.now(), title, technology, description, status: 'PENDING', author: 'Youssef O.', date: 'À l\'instant' });
  closeModal();
  document.getElementById('req-title').value = '';
  document.getElementById('req-technology').value = '';
  document.getElementById('req-description').value = '';
  renderAll();
  showSuccess('Demande publiée !', `Votre demande "${title}" est maintenant visible par les tuteurs.`);
}

function assignRequest(id) {
  const r = requests.find(r => r.id === id);
  if (!r) return;
  r.status = 'ASSIGNED';
  r.tutor = { name: 'Youssef O. (Vous)', initials: 'YO' };
  renderAll();
  showSuccess('Demande prise en charge !', `Vous avez accepté d'aider ${r.author}. Contactez-le pour planifier la session.`);
}

function resolveRequest(id) {
  const r = requests.find(r => r.id === id);
  if (!r) return;
  r.status = 'RESOLVED';
  renderAll();
  setTimeout(() => openRateModal(id), 400);
}

function openRateModal(id) {
  activeRatingId = id;
  const r = requests.find(r => r.id === id);
  currentRating = 1;
  setRating(1);
  document.getElementById('rate-subtitle').textContent = r.tutor ? `Notez ${r.tutor.name}` : 'Donnez une note à votre tuteur';
  document.getElementById('rate-comment').value = '';
  document.getElementById('modal-rate').style.display = 'flex';
}

function setRating(n) {
  currentRating = n;
  for (let i = 1; i <= 5; i++) {
    const s = document.getElementById('s' + i);
    s.className = i <= n ? 'stars' : 'stars star-empty';
  }
}

function submitRating() {
  if (currentRating < 1 || currentRating > 5) { alert('Note invalide (1 à 5 étoiles requises).'); return; }
  const r = requests.find(r => r.id === activeRatingId);
  if (r) r.rating = currentRating;
  closeModal();
  renderAll();
  showSuccess('Merci pour votre avis !', `Vous avez donné ${'★'.repeat(currentRating)} à votre tuteur.`);
}

function addSkill(type) {
  const inputId = type === 'mastered' ? 'new-skill-input' : 'new-learning-input';
  const val = document.getElementById(inputId).value.trim();
  if (!val) return;
  const containerId = type === 'mastered' ? 'skills-mastered' : 'skills-learning';
  const style = type === 'mastered' ? '' : 'style="background:#faeeda;color:#854f0b;"';
  const span = document.createElement('span');
  span.className = 'tag-skill';
  span.setAttribute('style', type === 'learning' ? 'background:#faeeda;color:#854f0b;' : '');
  span.textContent = val;
  document.getElementById(containerId).appendChild(span);
  document.getElementById(inputId).value = '';
}


function showSuccess(title, msg) {
  document.getElementById('success-title').textContent = title;
  document.getElementById('success-msg').textContent = msg;
  const m = document.getElementById('modal-success');
  m.style.display = 'flex';
  setTimeout(() => { m.style.display = 'none'; }, 3500);
}

// function renderAll() {
//   renderDashboard();
//   renderRequests(activeFilter, activeTech);
//   renderTutorSection();
//   renderLeaderboard();
//   renderAdmin();
// }


// renderAll();
</script>
</body>
</html>
