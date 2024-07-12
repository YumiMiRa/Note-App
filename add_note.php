<?php
// Check if request is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get JSON data from request body
    $data = json_decode(file_get_contents('php://input'), true);

    // Sanitize and get title, content, and id
    $title = htmlspecialchars($data['title']);
    $content = htmlspecialchars($data['content']);
    $id = htmlspecialchars($data['id']);

    // Create a new note array
    $note = [
        'title' => $title,
        'content' => $content,
        'id' => $id
    ];

    // Read existing notes from notes.json
    $notes = [];
    if (file_exists('notes.json')) {
        $notes = json_decode(file_get_contents('notes.json'), true);
    }

    // Add the new note to the array of notes
    $notes[] = $note;

    // Save the updated notes array back to notes.json
    file_put_contents('notes.json', json_encode($notes));

    // Return success response
    echo json_encode(['status' => 'success', 'message' => 'Note added successfully']);
    exit();
} else {
    // Return error response if accessed directly without POST data
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
    exit();
}
?>
