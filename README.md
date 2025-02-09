# Chronicle 

Version-Controlled Document Collaboration (Proof of Concept)

## Overview
This project is a **proof-of-concept** demonstrating **Event Sourcing** and **CQRS** in Laravel, using **Laravel Verbs** for event sourcing and **FilamentPHP** for the frontend. The application allows multiple users to **collaborate in real-time** on a shared document, with all edits stored as events.

Each document:
- **Refreshes every hour** and starts with a predefined introductory text.
- Allows **live collaboration**, where users see changes as others type.
- **Locks after an hour**, creating a new document while preserving the previous versions.
- Enables users to **replay document history** to visualize event sourcing in action.
- Displays an event log with options to **pause, rewind, and fast-forward** through changes.

## Goals
- **Showcase event sourcing and CQRS** with a practical, real-time example.
- Provide an interactive way for users to **see and understand events**.
- Keep the implementation **simple and accessible**, avoiding unnecessary complexity.

## Features
### Document Editing
- Users can **edit any part of the document** using Filament’s Markdown editor.
- **Live collaboration** with real-time updates using WebSockets.
- Documents are stored as **plain text**.

### User System
- Users sign up with **only a username and password** (no email required, but optional).
- Usernames must be unique.
- Future expansions may include reputation/history tracking.

### Event Replay & History
- Users can **replay past documents** to see how they evolved over time.
- A **pause, rewind, and fast-forward feature** enables event playback.
- A **filtering system** allows users to focus on specific types of events.

### Document Locking & Versioning
- After **one hour**, a document becomes **read-only**.
- Users can still **view and replay** events of locked documents.
- Only documents with user activity (beyond the initial event) are stored.

### FilamentPHP Panels
1. **Unauthenticated Panel**: Users can view history and replay past documents.
2. **Index Panel** (authenticated): Users can participate in the latest document and view past versions.
3. **Admin Panel** (authenticated): Provides moderation tools, analytics, and document management.

### Real-Time Updates
- **Livewire** will handle real-time form interactions.
- **WebSockets** will broadcast events instantly.
- **Alpine.js** may be used where beneficial.

## Technical Stack
- **Laravel 11**
- **Laravel Verbs** (for event sourcing)
- **FilamentPHP** (for admin and UI management)
- **Livewire** (for real-time interactions)
- **WebSockets** (for instant updates)
- **Plain text storage** for documents

## Future Enhancements
- **User reputation tracking** (e.g., contributions, consistency)
- **Conflict resolution** (if necessary, but not the main focus)
- **Interactive event visualization** (within Livewire/Filament limitations)
- **Admin dashboard metrics**
