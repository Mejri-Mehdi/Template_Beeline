# 🏗️ Project Architecture & Organization

This project complies with strict separation of concerns, descriptive naming, and uses PHP/Twig exclusively.

## 📂 1. Controllers (`src/Controller/`)

The controller layer is strictly organized by "Space" and uses descriptive names:

### 🌍 Public Space (`src/Controller/Public/`)
*For pages accessible to everyone*
- `HomeController`
- `SecurityController`
- `RegistrationController`

### 🤝 Shared Space (`src/Controller/Shared/`)
*For features common to multiple roles*
- `ProfileController`

### 🛡️ Admin Space (`src/Controller/Admin/`)
*Restricted to `ROLE_ADMIN`*
- `AdminDashboardController`
- `AdminUtilisateurController`
- `AdminBanqueController`
- `AdminRendezVousController`
- `AdminOffreController`
- `AdminServiceController`
- `AdminFinancementController`

### 👤 Client Space (`src/Controller/Front/`)
*Restricted to `ROLE_CLIENT`*
- `ClientDashboardController`
- `ClientRendezVousController`
- `ClientServiceController`
- `ClientOffreController`
- `ClientFinancementController`
- `ClientBanqueController`
- `ClientAutresBanquesController`

### 🏢 Agent Space (`src/Controller/Back/`)
*Restricted to `ROLE_AGENT`*
- `AgentDashboardController`
- `AgentRendezVousController`
- `AgentServiceController`
- `AgentOffreController`
- `AgentFinancementController`
- `AgentBanqueController`

---

## 🗄️ 2. Entities (`src/Entity/`)

Strictly aligned with UML design:

### User & Profile
- **Utilisateur**: Core user entity (Roles: Admin, Agent, Client).
- **Profile** (New, 1-to-1): Stores extended details (Experience, Preferences, Bio).

### Banking Core
- **Banque**: The bank entity.
- **Agence**: Branches of a bank.
- **Service**: Services offered (with `priorite_defaut`).

### Offers & Finance
- **Offre**: Bank offers (with `montant_min/max`).
- **Condition** (New, Many-to-1): Specific conditions for offers (thresholds, rates).
- **Financement**: Loan requests (with `type_dmd`).
- **Document** (New, Many-to-1): Documents attached to financing requests.

### Interactions
- **RendezVous**: Appointments (with `duree`, `priorite`).

---

## 🎨 3. Templates (`templates/`)

Mirroring controller structure:
- `templates/public/`
- `templates/shared/`
- `templates/front/`
- `templates/back/`
- `templates/admin/`

---

## ✅ Key Changes
- **Full Module Coverage**: Controllers for all defined modules.
- **UML Compliance**: Added `Profile`, `Condition`, `Document`, and all missing fields.
- **Strict Separation**: No logic in root namespaces.
- **Real Data**: Admin controllers wired to Repositories.
