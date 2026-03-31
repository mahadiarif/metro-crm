# Sales CRM - Technical Overview

This document provides a comprehensive technical overview of the Sales CRM project, built on **Laravel 12**.

## 1. Project Architecture

The application follows the standard Laravel MVC (Model-View-Controller) architecture, integrated with modern frontend tooling like **Livewire 3** and **Vite**.

*   **Folder Structure**:
    *   `app/Models`: Contains Eloquent models and their domain-specific logic.
    *   `app/Http/Controllers`: House the application's business logic, including a dedicated `Dashboard` sub-namespace.
    *   `app/Livewire`: (Where applicable) Components for dynamic UI elements.
    *   `database/migrations`: Defines the evolving schema of the CRM.
    *   `resources/views`: Blade templates, including `livewire` components and dashboard-specific views.
    *   `routes`: Separated into `web.php` for UI/Dashboard and `api.php` for external/internal API endpoints.

## 2. Database Structure

The database consists of tables tracking the sales lifecycle, user management, and marketing campaigns.

| Table Name | Description | Key Relationships |
| :--- | :--- | :--- |
| `users` | System users (Execs, Managers, Admins). | Belongs to Team (Team Leader), Has Many Roles. |
| `leads` | Potential customers and their status. | Belongs to Service, Stage, Assigned User. Has Many Visits, Followups, Sales. |
| `visits` | Records of physical or virtual client visits. | Belongs to Lead, User, Service. |
| `follow_ups` | Scheduled reminders and logs of client interaction. | Belongs to Lead, User. |
| `sales` | Finalized deals and revenue records. | Belongs to Lead, Service, Service Package, User. |
| `services` | Product or service categories offered. | Has Many Leads, Visits, Sales. |
| `service_packages` | Specific pricing or feature tiers for services. | Belongs to Service. |
| `pipeline_stages` | Defined steps in the sales funnel (Kanban stages). | Has Many Leads. |
| `roles` / `privileges` | RBAC tables for access control. | Polymorphic Many-to-Many via `user_roles` and `privilege_role`. |
| `campaigns` | Marketing outreach campaigns. | Has Many Recipients. |

## 3. Eloquent Models & Relations

*   **Lead**: The central entity.
    *   `stage()`: BelongsTo `PipelineStage`
    *   `service()`: BelongsTo `Service`
    *   `visits()`: HasMany `Visit`
    *   `followUps()`: HasMany `FollowUp`
    *   `sale()`: HasOne `Sale`
    *   `proposals()`: HasMany `Proposal`
*   **Visit**: Records client interest during a visit.
    *   `lead()`: BelongsTo `Lead`
    *   `interests()`: HasMany `VisitServiceInterest` (Multiple services discussed during one visit)
*   **Sale**: The final conversion.
    *   `lead()`: BelongsTo `Lead`
    *   `user()`: BelongsTo `User` (The closer)
*   **User**: Handles Authentication and RBAC.
    *   `roles()`: BelongsToMany `Role`
    *   `teamMembers()`: HasMany `User` (Self-referencing for Team Leaders)

## 4. Controllers

| Controller | Responsibility |
| :--- | :--- |
| `LeadController` | CRUD operations for leads and initial assignment. |
| `UserController` | Management of system users, roles, and 2FA resets. |
| `Dashboard\AnalyticsController` | Aggregates data for charts and overview stats. |
| `Dashboard\ReportController` | Generates Sales, Visit, and Lead reports (PDF/CSV export). |
| `Dashboard\PipelineController` | Manages the Kanban board and stage transitions. |
| `Dashboard\MarketingController` | Orchestrates campaigns and template management. |
| `Dashboard\ProposalController` | Handles creation and status tracking of client proposals. |

## 5. Main Routes

### Web Routes (`web.php`)
All dashboard routes are protected by `auth` and `tyro.role.protection` middleware.
*   `/dashboard`: Redirects to the main dashboard index.
*   `/dashboard/reports/*`: Sales, Visits, Leads, Quarterly, and Team performance reports.
*   `/dashboard/pipelines/kanban`: The visual sales funnel.
*   `/dashboard/marketing/*`: Campaign management and templates.
*   `/dashboard/users`: User management (Admin only).
*   `/dashboard/proposals`: Proposal creation and tracking.

### API Routes (`api.php`)
*   `/api/user`: Returns authenticated user details (Sanctum protected).

## 6. Implemented Modules

*   **Leads**: Captures company info, contact person, existing provider, and current usage.
*   **Services**: Manages the catalog of offerings and their specific packages.
*   **Visits**: Tracks visit numbers, meeting notes, and "Interest Summary Status".
*   **Followups**: Schedules the next interaction date to prevent lead leakage.
*   **Sales**: Records finalized amounts, closure dates, and remarks.
*   **Dashboard**: Centralized hub with real-time stats and visual pipelines.
*   **Marketing**: Tools for running campaigns, using templates, and tracking recipients.
*   **Reports**: Dynamic reporting engine for performance auditing and data exports.

## 7. Dashboard Components

The system utilizes **Tyro Dashboard** components and custom **Livewire** widgets:
*   **Sales Target Widget**: Tracks progress against quarterly goals.
*   **Visit Counters**: Displays daily/weekly visit counts.
*   **Lead Pipeline Chart**: Visualizes the distribution of leads across stages.
*   **User Performance Stats**: Individual and team-level metrics (Conversion rates, total sales).
*   **Kanban Board**: Drag-and-drop interface for progressing leads through stages.

## 8. RBAC Structure

The project uses a custom Middleware (`TyroRoleProtection`) to enforce hierarchy:

*   **Super Admin**: Unrestricted access to all modules, including user management and audit logs.
*   **Manager**: Access to reports, marketing, and all lead data. No user management privileges.
*   **Team Leader**: Access to their own data and data belonging to their assigned team members.
*   **Marketing Executive**: Restricted to their own assigned leads, visits, and personal stats.

> [!NOTE]
> Privilege-based checks like `leads.view_all` or `leads.view_team` are implemented via Eloquent Global Scopes to ensure data isolation at the database level.

## 9. External Packages Used

*   **Laravel 12**: Core framework.
*   **Tyro Dashboard**: Premium admin interface and UI components.
*   **Livewire 3**: Dynamic, reactive interfaces without writing heavy JavaScript.
*   **BarryVDH Laravel DomPDF**: Used for generating downloadable PDF reports (e.g., Sales Reports).
*   **Laravel Sanctum**: API authentication for secure data exchange.
*   **Pail & Sail**: Development and debugging tools.

## 10. Overall System Workflow

The "Lead Lifecycle" follows a structured path designed to maximize conversion:

1.  **Lead Creation**: A lead is captured (manually or via API) with initial requirements and assigned to an Executive.
2.  **Initial Visit**: The Executive conducts a visit, records client interest in specific services, and logs meeting notes.
3.  **Follow-up Loop**: Based on the visit, the next follow-up date is set. Multiple follow-ups may occur to nurture the lead.
4.  **Proposal Stage**: A formal proposal can be generated and sent for review.
5.  **Sales Closure**: Once the client agrees, the lead is converted to a "Sale". The system records the final amount and closes the lead.
6.  **Reporting**: All actions are audited and aggregated into performance reports for management review.
