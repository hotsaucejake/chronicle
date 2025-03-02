<x-filament-panels::page>
<p>TODO: clean this up and make it pretty - this is all mardown for now</p>

    **Project Summary – Chronicle: Version-Controlled Document Collaboration**

    Chronicle is a proof‑of‑concept web application built with Laravel that demonstrates a practical implementation of event sourcing and CQRS. It’s designed for collaborative document editing where every change is recorded as an event, allowing users to replay, audit, and understand how documents evolve over time. Here’s what a user can expect when they explore the system:

    ### What the Project Is

    - **Real-Time Collaborative Editing:**
    Users can register (using just a username and password—email isn’t required) and immediately start editing documents. As you type, changes are captured as events and broadcast in real time using WebSockets (powered by Laravel Reverb). This ensures that if multiple users are editing the same document simultaneously, everyone sees live updates.

    - **Dual Event Sourcing Implementations:**
    The project implements two distinct event‑sourcing approaches side‑by‑side:
    - **Verbs:**
    A state‑first approach where events directly modify a “state” object. It emphasizes simplicity and quick prototyping, with built‑in optimistic concurrency control (using a previous_version property) and revision tracking.
    - **Spatie’s Laravel Event Sourcing:**
    A more traditional implementation that clearly separates the write side (aggregates emitting events) from the read side (projectors updating read‑only projections). This method supports advanced features like snapshots (taken every few edits, for example, every 5 edits) and event replay for auditing and debugging.

    - **Document Locking & Expiration:**
    Documents are designed to lock every hour—meaning that after one hour of editing, the document becomes read‑only and a new document is created for further revisions. However, if a document hasn’t been touched (no edits), its expiration is automatically extended. This ensures that only active, edited documents become locked, preserving work and history.

    - **Live Collaboration Indicators:**
    When you’re editing, you’ll see indicators if another user is also editing the document. This includes real‑time notifications of other active editors as well as live updates if another user saves their changes while you’re still editing.

    - **Event & Snapshot Viewing:**
    Beyond the live document view, Chronicle offers interfaces to view read‑only representations of the underlying events and snapshots. This gives users—and developers—a transparent view into the event sourcing mechanism, showing how every change is recorded and how the current state is derived from a history of events.

    ### What the Project Is NOT

    - **Conflict Resolution Using CRDT:**
    While Chronicle showcases real‑time editing, it does not implement Conflict-free Replicated Data Types (CRDT) for merging simultaneous edits. This means that if two users edit the same section of a document at the same time, conflicts aren’t automatically resolved using CRDT algorithms. Handling such conflicts remains an area for future exploration and improvement.

    ### Pros and Cons of the Two Event Sourcing Implementations

    #### **Verbs (State‑First Approach)**
    - **Pros:**
    - **Simplicity & Quick Prototyping:**
    The state‑first model makes it straightforward to get started. Events directly mutate a state object, making the flow easier to grasp for smaller applications.
    - **Built‑In Optimistic Concurrency:**
    By tracking a previous version, it can prevent conflicts in a simple manner.
    - **Integrated Revision Tracking:**
    A dedicated model for document revisions is maintained, allowing you to see the complete history of changes.
    - **Cons:**
    - **Tighter Coupling of State and Events:**
    The close integration can make it less flexible as complexity grows.
    - **Limited Separation of Concerns:**
    In more complex domains, having a clear separation between write and read models is beneficial; the state‑first approach can blur these lines.

    #### **Spatie’s Laravel Event Sourcing (Traditional Approach)**
    - **Pros:**
    - **Clear Separation of Concerns:**
    Aggregates emit events, projectors listen to those events and update projections (read models), ensuring a clean decoupling between command and query sides.
    - **Scalability & Flexibility:**
    The traditional pattern supports advanced features like snapshots, event replay, and reactors for side effects, which can be invaluable as your application grows.
    - **Industry-Standard Practices:**
    This approach adheres closely to established event sourcing and CQRS best practices.
    - **Cons:**
    - **More Boilerplate & Complexity:**
    It requires setting up multiple components (aggregates, projectors, projections), which can result in more code and a steeper learning curve.
    - **Aggregate Identity Management:**
    Ensuring consistent UUIDs across aggregates and projections is crucial and can add some overhead to implementation.
    - **Slower to Prototype:**
    The traditional approach may take longer to implement initially compared to a simpler state‑first model.

    ### In Summary

    Chronicle is an innovative experimental platform that leverages event sourcing and CQRS to offer real-time collaborative document editing. Whether you’re interacting with the Verbs or the Spatie implementation, you’ll see live updates, document locking behavior, and a full audit trail of every change. Users can explore how documents evolve over time through a detailed event history and snapshot views.

    While Chronicle demonstrates two approaches to event sourcing—each with its own advantages and trade-offs—it intentionally does not implement CRDT-based conflict resolution, leaving that as a potential area for future enhancement.

    This project is ideal for developers and curious users who want to understand the mechanics of event sourcing in a modern Laravel application, and it serves as a platform for experimenting with real-time collaboration concepts.
</x-filament-panels::page>
