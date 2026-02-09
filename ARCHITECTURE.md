# 🏗️ Project Architecture & Organization

This project complies with strict separation of concerns, descriptive naming, and uses PHP/Twig exclusively.

## 📂 1. Controllers (`src/Controller/`)

The controller layer is strictly organized by "Space" and uses descriptive names:

### 🌍 Public Space (`src/Controller/Public/`)
*For pages accessible to everyone (User, Anonymous)*
- namespace: `App\Controller\Public`
- `HomeController`: Landing page
- `SecurityController`: Login, Logout
- `RegistrationController`: Registration

### 🤝 Shared Space (`src/Controller/Shared/`)
*For features common to multiple roles*
- namespace: `App\Controller\Shared`
- `ProfileController`: User profile management

### 🏢 Agent Space (`src/Controller/Back/`)
*Restricted to `ROLE_AGENT`. Controllers are prefixed with `Agent`.*
- namespace: `App\Controller\Back`
- `AgentDashboardController`: Main Agent Dashboard
- `AgentBanqueController`: Management of Bank and Agencies
- `AgentOffreController`: Management of Offers
- `AgentRendezVousController`: Management of Appointments
- `Agent*Controller`: Other agent features

### 👤 Client Space (`src/Controller/Front/`)
*Restricted to `ROLE_CLIENT`. Controllers are prefixed with `Client`.*
- namespace: `App\Controller\Front`
- `ClientDashboardController`: Main Client Dashboard
- `ClientBanqueController`: Bank viewing for clients
- `ClientOffreController`: Offer viewing
- `ClientRendezVousController`: Appointment booking
- `Client*Controller`: Other client features

### 🛡️ Admin Space (`src/Controller/Admin/`)
*Restricted to `ROLE_ADMIN`. Controllers are prefixed with `Admin`.*
- namespace: `App\Controller\Admin`
- `AdminDashboardController`: Main Admin Dashboard
- `AdminBanqueController`: Bank administration
- `AdminUtilisateurController`: User administration
- `Admin*Controller`: Other admin features

---

## 🎨 2. Templates (`templates/`)

The view layer mirrors the controller structure:
- `templates/public/`
- `templates/shared/`
- `templates/front/` (Client)
- `templates/back/` (Agent)
- `templates/admin/` (Admin)

---

## 📝 Key Changes Implemented
- **Descriptive Naming**: usage of `Agent...`, `Client...`, `Admin...` prefixes for clarity.
- **Strict Separation**: No logic in root namespace.
- **Cleanup**: Removal of mock/legacy controllers (`BanqueController`, `AgenceController`) in favor of real implementations (`AgentBanqueController`, etc.).
- **Sidebar Integration**: Sidebar links updated to point to the correct, functional controllers.
