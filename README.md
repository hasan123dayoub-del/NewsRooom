📝 Technical Documentation & Setup Guide - NewsRoom Platform
Welcome to the official technical repository of NewsRoom, a high-performance, decoupled Enterprise CMS platform developed for TechNova. This project strictly follows modern software architecture patterns, focusing on scalability, security, and clean code principles.

🚀 1. Setup Instructions (Step-by-Step)
Follow these steps to deploy and configure the environment on a local development machine (Optimized for XAMPP / PHP 8.2+ / Windows):

Prerequisites
PHP: ^8.2 (with openssl, pdo, mbstring extensions enabled)

Web Server: Apache 2.4+ (XAMPP Environment)

Database: MySQL / MariaDB

Tools: Composer, Git, Postman (for API testing)

Installation Steps
Clone the Repository:

Bash
git clone https://github.com/your-username/newsroom-api.git
cd newsroom-api
Install Dependencies:
Run Composer to install all required framework packages:

Bash
composer install
Environment Configuration:
Copy the example environment file and configure your local settings:

Bash
cp .env.example .env
Open the .env file and update your database credentials:

Code snippet
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=newsroom_db
DB_USERNAME=root
DB_PASSWORD=

CACHE_STORE=redis # Or database/file depending on your local config
QUEUE_CONNECTION=database
Generate Application Key:

Bash
php artisan key:generate
Database Migrations and Seeding:
Run the migrations to build schemas and seed the initial tags/roles:

Bash
php artisan migrate --seed
Serve the Application:
Since you are using XAMPP, you can map the domain in your Apache vhosts file or run the built-in development server:

Bash
php artisan serve
The API will be accessible at: http://127.0.0.1:8000

🗄️ 2. Entities & Database Relationships
The system database schema is strictly normalized and indexed to support intense concurrent reads/writes without performance bottlenecks.

   [ User ] 1 ─────── 1 [ Profile ]
      │
      └─ 1 ─────── 1..* [ Article ] 1..* ─────── 1..* [ Tag ]  (Polymorphic / Pivot)
                           │
                           └─ 1 ─────── 1..* [ Comment ]
                           │
                           └─ 1 ─────── 1..* [ Attachment ]
Core Entities Description:
User & Profile (1:1 Relationship):
Each registered employee (admin, writer, reader) has exactly one static profile containing descriptive metadata.

Implementation: $user->profile() and $profile->user().

User & Article (1:N Relationship):
A user can write multiple articles, but each article belongs to only one author.

Implementation: $user->articles() and $article->user().

Article & Comment (1:N Relationship):
An article can have multiple comments left by readers or internal reviewers.

Implementation: $article->comments() and $comment->article().

Article & Tag (N:M Many-to-Many Relationship):
Articles are dynamically categorized using multi-tags through a pivot relationship to maximize indexing efficiency.

Implementation: $article->tags() and $tag->articles().

Article & Attachment (1:N Relationship):
Supports rich media rendering by attaching uploaded file entities to articles.

🏛️ 3. Architectural Decisions & Rationales (ADRs)
To meet the high standards demanded by the CTO, several advanced architectural practices were implemented:

A. Repository Pattern & Contract-Driven Design
Decision: Encapsulated all database query mechanics inside an abstract layer (ArticleRepositoryInterface), injected dynamically into the controllers via Laravel's Service Container.

Why? It ensures Loose Coupling. The controllers are 100% blind to Eloquent and MySQL. If the business scales and switches to MongoDB or an external microservice, we only change a single line in AppServiceProvider without refactoring controllers.

B. Decoupled Service Layer (Slim Controllers)
Decision: Extracted heavy core business logic (e.g., synchronizing tag relationships, processing input mutations) out of the HTTP request lifecycle into specialized domain classes (ArticleService, DashboardService).

Why? Adheres strictly to the Single Responsibility Principle (SRP). Controllers remain thin, clean, and focus only on receiving HTTP requests and returning clean API payloads.

C. Contextual Binding for Notifications
Decision: Implemented NotificationSenderInterface dynamically resolved via context rules for AdminArticleController and WriterArticleController.

Why? Avoids massive if/else statements checking user roles. The framework knows automatically when to route notifications through database logging or external email channels at runtime.

D. Multi-Version API Isolation (V1/V2 Architecture)
Decision: Separated the Web Client endpoints (/api/v1/) from the Mobile Client endpoints (/api/v2/) using inheritance-based controllers.

Why? Mobile devices require rich aggregated data objects (tags, comments_count) to avoid round-trip network lag. By utilizing polymorphic JsonResource structures, we deliver rich data payloads to V2 clients without breaking existing V1 legacy integrations.

E. Advanced Cache Stampede Protection (Atomic Locking)
Decision: Integrated Redis Atomic Locks ($lock->block()) inside the dashboard infrastructure layer.

Why? Prevents system crashes during peak traffic spikes. When high-velocity cache expiration occurs, only one worker thread is permitted to query the underlying database to rebuild the cache matrix, keeping database overhead completely flat.

F. After-Middleware Telemetry Logging
Decision: Implemented custom logging capturing execution metrics utilizing the terminate() lifecycle function of Laravel's middleware.

Why? Offloads logging processing overhead completely until after the HTTP response has been safely transmitted to the client, assuring optimal API latency.

🔐 Security & Authentication
NewsRoom employs **Laravel Sanctum** for lightweight, robust API authentication. The architecture ensures stateless session-less communication suitable for both web and mobile clients.

🔑 Authentication Flow:
- **Registration:** Secure user creation with password hashing (Bcrypt).
- **Login:** Token-based authentication returning a `Bearer Token`.
- **User Transformation:** We utilize `UserResource` to ensure that API responses are decoupled from the database schema, providing only the necessary public-facing metadata.

🛠️ Authentication Endpoints:
| Method | Endpoint        | Description                          | Auth Required |
| :----- | :-------------- | :----------------------------------- | :------------ |
| `POST` | `/api/register` | Create a new user account            | No            |
| `POST` | `/api/login`    | Authenticate & retrieve access token | No            |
| `POST` | `/api/logout`   | Revoke current access token          | Yes           |

🚀 Automation in Postman:
To streamline testing, we have implemented an **Auto-Token Injection** mechanism in the Postman collection:
1. Navigate to the `login` request inside the `Authentication` folder.
2. In the **Tests** tab, a script automatically captures the `access_token` and saves it to your `sanctum_token` environment variable.
3. All subsequent requests in the collection are configured to use this token automatically as a `Bearer Token`.


📂 Api Collectios :

📌 [Click here to download the file and try it](<TechNova NewsRoom API - 2026.postman_collection.json>)

Developed with pure architectural discipline for TechNova Enterprise System - 2026.
 