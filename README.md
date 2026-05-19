# PeerSync
📘 PeerSync

PeerSync is a PHP-based peer-to-peer tutoring platform designed for intensive coding bootcamps like ENAA. It improves the way students request help and connect with available tutors in a structured and trackable system.

Instead of relying on informal chat systems (like Discord), PeerSync centralizes all help requests, making them easier to manage, track, and evaluate.

🚀 Project Goal

The main goal of PeerSync is to:

Organize student help requests in one platform
Connect learners with available tutors efficiently
Track tutoring sessions and their outcomes
Measure the impact of peer learning
Reward active tutors through engagement metrics
🧠 Key Features
👨‍🎓 Students
Create help requests with title, description, and technology
Track request status (Pending, Assigned, Resolved)
Close sessions when problem is solved
Rate tutors (1 to 5 stars)
👨‍🏫 Tutors
View incoming help requests
Accept and assign themselves to a request
Help students through structured sessions
Earn points and badges based on activity
🛠️ Admin
Monitor platform activity
View statistics (most requested technologies, active tutors)
Track tutoring impact and engagement
⚙️ Technical Features
Pure PHP (Object-Oriented Programming)
PDO for secure database interaction
Repository Pattern for clean architecture
Strict typing (declare(strict_types=1))
Encapsulated entities (private properties + getters/setters)
Enum-based status management
Proper data hydration (no raw PDO arrays in views)
🗂️ Project Structure
entities/ → Core business objects (User, HelpRequest, Review)
repositories/ → Database access layer (PDO queries only)
actions/ → Form processing logic
pages/ → UI pages (student, tutor, admin dashboards)
config/ → Database connection
enums/ → Application states (Status, Role)
📊 Example Workflow
Student creates a help request
Request appears on tutor dashboard
Tutor accepts and takes responsibility
Session is completed and marked as resolved
Student rates the tutor
System updates statistics and rewards tutor points
🏆 Learning Outcomes

This project demonstrates:

Strong PHP OOP design
Clean separation of responsibilities
Database design and relational modeling (MySQL)
Real-world workflow management system
Scalable backend architecture without frameworks
📌 Status

🚧 In development (bootcamp project – ENAA)
