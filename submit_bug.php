<? php
 session_start();
 ?>
 
<form action="process_bug.php" method="post" enctype="multipart/form-data">
    <label>Title:</label>
    <input type="text" name="title" required><br>

    <lable>Description:</label>
    <textarea name="description" required></textarea><br>

    <label>Steps to Reproduce:</label>
    <textarea name="steps"></textarea><br>

    <label>Severity:</label>
    <select name="severity">
    <option value="Low">Low</option>
        <option value="Medium">Medium</option>
        <option value="High">High</option>
        <option value="Critical">Critical</option>
    </select><br>

    <label>Attachment:</label>
    <input type="file" name="attachment"><br>

    <label>Email:</label>
    <input type="text" name="email" required><br>

    <button type="submit">Submit Bug</button>

<form>

