<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Event Management System</title>
</head>

<body>
    <h2>Events</h2>
    <ul id="eventList"></ul>

    <h2>Create Event</h2>
    <form id="createEventForm">
        <label>Title: <input type="text" name="title" required></label><br>
        <label>Description: <textarea name="description"></textarea></label><br>
        <label>Date and Time: <input type="datetime-local" name="date_time" required></label><br>

        <!-- Add a field to select the event manager (user) -->
        <label>Event Manager:
            <select name="event_manager" required>
                <!-- Fetch and display users -->
                <!-- You might want to improve this part, like using a separate API endpoint for fetching users -->
                <?php
                $usersResult = $conn->query("SELECT * FROM users");
                while ($user = $usersResult->fetch_assoc()) {
                    echo "<option value=\"{$user['id']}\">{$user['username']}</option>";
                }
                ?>
            </select>
        </label><br>

        <button type="submit">Create Event</button>
    </form>

    <script>
    // Fetch and display events
    fetch('api.php')
        .then(response => response.json())
        .then(events => {
            const eventList = document.getElementById('eventList');
            eventList.innerHTML = events.map(event =>
                `<li>${event.title} - ${event.date_time} (Manager: ${event.event_manager})</li>`).join('');
        });

    // Handle form submission to create a new event
    document.getElementById('createEventForm').addEventListener('submit', function(event) {
        event.preventDefault();
        const formData = new FormData(this);

        fetch('api.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
                location.reload();
            });
    });
    </script>
</body>

</html>