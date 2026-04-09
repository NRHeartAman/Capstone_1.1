<?php if ($_SESSION['role'] === 'Owner'): ?>
    <div class="admin-section">
        <h3>Add New Staff Member</h3>
        <form action="admin_actions.php" method="POST">
            <input type="text" name="new_username" placeholder="Username" required>
            <input type="email" name="new_email" placeholder="Email" required>
            <input type="password" name="new_password" placeholder="Password" required>
            <select name="new_role">
                <option value="Staff">Staff (Admin in Form)</option>
                <option value="Owner">Owner (User in Form)</option>
            </select>
            <button type="submit" name="add_user">Add User</button>
        </form>
    </div>

    <div class="log-section">
        <h3>Recent Activity Logs</h3>
        <table border="1">
            <tr>
                <th>User</th>
                <th>Action</th>
                <th>Time</th>
            </tr>
            <?php
            $logs = $conn->query("SELECT * FROM activity_log ORDER BY timestamp DESC LIMIT 10");
            while($row = $logs->fetch_assoc()): ?>
            <tr>
                <td><?= $row['username']; ?></td>
                <td><?= $row['action']; ?></td>
                <td><?= $row['timestamp']; ?></td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>
<?php endif; ?>