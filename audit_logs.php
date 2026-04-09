<div id="audit-tab" class="tab-content">
    <div class="user-card audit-list">
        <h3 style="margin-bottom: 15px;">System Activity Logs</h3>
        <?php
        // Query para sa huling 10 activities
        $logs = mysqli_query($conn, "SELECT logs.*, users.username FROM audit_logs logs 
                                    JOIN users ON logs.user_id = users.id 
                                    ORDER BY logs.timestamp DESC LIMIT 10");

        if ($logs && mysqli_num_rows($logs) > 0) {
            while($log = mysqli_fetch_assoc($logs)) {
                echo "<div class='audit-row' style='border-bottom: 1px solid #eee; padding: 10px 0;'>
                        <strong>" . htmlspecialchars($log['username']) . "</strong> " . htmlspecialchars($log['action']) . " <br>
                        <small style='color:#aaa;'>" . date("M d, Y | g:i A", strtotime($log['timestamp'])) . "</small>
                      </div>";
            }
        } else {
            echo "<p style='color:#999; padding:10px;'>No activities recorded yet.</p>";
        }
        ?>
    </div>
</div>