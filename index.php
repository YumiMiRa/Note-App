<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Note</title>
    <style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 800px;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        header {
            text-align: center;
            margin-bottom: 20px;
        }
        h1 {
            color: #333;
        }
        .content {
            text-align: center;
            margin-bottom: 20px;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .btn:hover {
            background-color: #0056b3;
        }
        /* Note Styles */
        .note-container {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .note {
            width: 100%;
            margin-bottom: 20px;
            padding: 15px;
            background-color: #f9f9f9; /* Default background color for note box */
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            text-align: left;
            position: relative;
            overflow: hidden; /* Ensures note box doesn't expand */
            transition: background-color 0.3s ease;
        }
        .note:hover {
            background-color: #e9ecef; /* Lighter background color on hover */
        }
        .note h3 {
            margin-top: 0;
            color: #007bff;
            font-size: 18px;
        }
        .note p {
            margin-bottom: 10px;
            color: #666;
            font-size: 14px;
            white-space: pre-line; /* Preserve line breaks */
        }
        .note-actions {
            position: absolute;
            top: 5px;
            right: 5px;
            opacity: 0; /* Hidden by default */
            transition: opacity 0.3s ease;
        }
        .note:hover .note-actions {
            opacity: 1; /* Show actions on hover */
        }
        .note-actions button {
            color: white;
            padding: 5px 10px;
            border: none;
            cursor: pointer;
            margin-left: 5px;
            border-radius: 3px;
        }
        .note-actions .edit-btn {
            background-color: #ffc107; /* Yellow color for edit button */
        }
        .note-actions .delete-btn {
            background-color: #dc3545; /* Red color for delete button */
        }
        /* Input Styles */
        .note-input-container {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }
        .note-input {
            width: calc(100% - 90px); /* Adjust width to fit within note container */
            padding: 10px;
            background-color: #f9f9f9; /* Background color for input */
            border: none; /* Remove border */
            border-radius: 5px;
            font-size: 14px;
            margin-right: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); /* Add box shadow for depth */
        }
        .add-note-btn {
            padding: 10px 20px;
            background-color: #28a745; /* Green color for add note button */
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .add-note-btn:hover {
            background-color: #218838; /* Darker green color on hover */
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>Note</h1>
        </header>

        <div class="content" id="welcomeContainer">
            <h2>Welcome!</h2>
            <a href="#" class="btn" id="startBtn">Start Taking Notes</a>
        </div>

        <div class="content" id="notesContainer" style="display: none;">
            <div class="note-input-container">
                <input type="text" id="noteInput" class="note-input" placeholder="Enter your note...">
                <button class="add-note-btn" id="addNoteBtn">Add Note</button>
            </div>
            <div class="note-container" id="notesList">
                <!-- Notes will be dynamically added here -->
            </div>
            <a href="#" class="btn" id="backBtn">Back</a>
        </div>

        <footer>
            <p>&copy; 2024 Note. All rights reserved.</p>
        </footer>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const welcomeContainer = document.getElementById('welcomeContainer');
        const notesContainer = document.getElementById('notesContainer');
        const startBtn = document.getElementById('startBtn');
        const backBtn = document.getElementById('backBtn');
        const addNoteBtn = document.getElementById('addNoteBtn');
        const noteInput = document.getElementById('noteInput');
        const notesList = document.getElementById('notesList');
        let notesData = [];

        // Color ranges for each day
        const colorRanges = {
            "Sunday": [
                { "start": 6.00, "end": 11.59, "color": "#FF8986" },
                { "start": 12.00, "end": 12.59, "color": "#FF9997" },
                { "start": 13.00, "end": 15.59, "color": "#FFA9A9" },
                { "start": 16.00, "end": 18.59, "color": "#FC9994" },
                { "start": 19.00, "end": 23.59, "color": "#F98581" },
                { "start": 0.00, "end": 5.59, "color": "#FF7B7B" }
            ],
            "Monday": [
                { "start": 6.00, "end": 11.59, "color": "#FDDF8E" },
                { "start": 12.00, "end": 12.59, "color": "#FBEE95" },
                { "start": 13.00, "end": 15.59, "color": "#FCF695" },
                { "start": 16.00, "end": 18.59, "color": "#FEEFC6" },
                { "start": 19.00, "end": 23.59, "color": "#FEE7AA" },
                { "start": 0.00, "end": 5.59, "color": "#F9E795" }
            ],
            "Tuesday": [
                { "start": 6.00, "end": 11.59, "color": "#FFB3C6" },
                { "start": 12.00, "end": 12.59, "color": "#FFC2D1" },
                { "start": 13.00, "end": 15.59, "color": "#FFE5EC" },
                { "start": 16.00, "end": 18.59, "color": "#F5DCE0" },
                { "start": 19.00, "end": 23.59, "color": "#EFCFD4" },
                { "start": 0.00, "end": 5.59, "color": "#ECBDC4" }
            ],
            "Wednesday": [
                { "start": 6.00, "end": 11.59, "color": "#BEE3BA" },
                { "start": 12.00, "end": 12.59, "color": "#CDEBC5" },
                { "start": 13.00, "end": 15.59, "color": "#DDF2D1" },
                { "start": 16.00, "end": 18.59, "color": "#E4F3D8" },
                { "start": 19.00, "end": 23.59, "color": "#C8E6B2" },
                { "start": 0.00, "end": 5.59, "color": "#AEDCAE" }
            ],
            "Thursday": [
                { "start": 6.00, "end": 11.59, "color": "#FFB58A" },
                { "start": 12.00, "end": 12.59, "color": "#AEDCAE" },
                { "start": 13.00, "end": 15.59, "color": "#FFD1AD" },
                { "start": 16.00, "end": 18.59, "color": "#FFD9B2" },
                { "start": 19.00, "end": 23.59, "color": "#FFD0A7" },
                { "start": 0.00, "end": 5.59, "color": "#FFC79C" }
            ],
            "Friday": [
                { "start": 6.00, "end": 11.59, "color": "#A6C3E4" },
                { "start": 12.00, "end": 12.59, "color": "#COD4E1" },
                { "start": 13.00, "end": 15.59, "color": "#AFD5F0" },
                { "start": 16.00, "end": 18.59, "color": "#DAFAFA" },
                { "start": 19.00, "end": 23.59, "color": "#C3EEFA" },
                { "start": 0.00, "end": 5.59, "color": "#93B9DD" }
            ],
            "Saturday": [
                { "start": 6.00, "end": 11.59, "color": "#CCB7E5" },
                { "start": 12.00, "end": 12.59, "color": "#D9C4EC" },
                { "start": 13.00, "end": 15.59, "color": "#E6D1F2" },
                { "start": 16.00, "end": 18.59, "color": "#DDD5F3" },
                { "start": 19.00, "end": 23.59, "color": "#CEC2EB" },
                { "start": 0.00, "end": 5.59, "color": "#C0AFE2" }
            ]
        };

        // Start taking notes button click event
        startBtn.addEventListener('click', function(e) {
            e.preventDefault();
            welcomeContainer.style.display = 'none';
            notesContainer.style.display = 'block';
            renderNotes();
        });

        // Back button click event
        backBtn.addEventListener('click', function(e) {
            e.preventDefault();
            notesContainer.style.display = 'none';
            welcomeContainer.style.display = 'block';
            noteInput.value = ''; // Clear input field
        });

        // Function to render notes from notesData array
        function renderNotes() {
            notesList.innerHTML = ''; // Clear existing notes

            notesData.forEach((note, index) => {
                const noteElem = createNoteElement(note, index);
                notesList.appendChild(noteElem);
            });
        }

        // Function to create a note element
        function createNoteElement(note, index) {
            const noteElem = document.createElement('div');
            noteElem.classList.add('note');
            noteElem.style.border = 'none'; // Remove border from note element
            noteElem.style.boxShadow = '0 2px 5px rgba(0, 0, 0, 0.1)'; // Add box shadow for depth

            // Calculate background color based on current day and time
            const dayOfWeek = new Date().getDay(); // Get current day of the week (0 - 6)
            const currentHour = new Date().getHours() + (new Date().getMinutes() / 60); // Get current hour as decimal

            // Find matching color range for current day and time
            const dayName = getDayName(dayOfWeek);
            const colorRange = getColorRange(colorRanges[dayName], currentHour);

            noteElem.style.backgroundColor = colorRange.color;

            noteElem.innerHTML = `
                <h3>${note.dateTime}</h3>
                <p>${note.text}</p>
                <div class="note-actions">
                    <button class="edit-btn">Edit</button>
                    <button class="delete-btn">Delete</button>
                </div>
            `;

            // Edit button click event
            const editBtn = noteElem.querySelector('.edit-btn');
            editBtn.addEventListener('click', function() {
                const newText = prompt('Enter updated note:', note.text);
                if (newText !== null && newText.trim() !== '') {
                    note.text = newText.trim();
                    note.dateTime = new Date().toLocaleString(); // Update date/time
                    renderNotes();
                }
            });

            // Delete button click event
            const deleteBtn = noteElem.querySelector('.delete-btn');
            deleteBtn.addEventListener('click', function() {
                notesData.splice(index, 1); // Remove note from array
                renderNotes(); // Re-render notes
            });

            return noteElem;
        }

        // Function to get day name from day index
        function getDayName(dayIndex) {
            const days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
            return days[dayIndex];
        }

        // Function to get color range for current hour
        function getColorRange(colorRanges, currentHour) {
            for (let i = 0; i < colorRanges.length; i++) {
                const range = colorRanges[i];
                if (currentHour >= range.start && currentHour <= range.end) {
                    return range;
                }
            }
            // Default fallback color if no range matches
            return { color: '#f9f9f9' };
        }

        // Add note button click event
        addNoteBtn.addEventListener('click', function(e) {
            e.preventDefault();
            const noteText = noteInput.value.trim();
            const today = new Date();
            const newNote = {
                text: noteText,
                dateTime: today.toLocaleString()
            };

            if (noteText !== '') {
                notesData.push(newNote); // Add new note to array
                renderNotes(); // Re-render notes
                noteInput.value = ''; // Clear input field after adding note
            } else {
                alert('Please enter a note.');
            }
        });
    });
</script>

</body>
</html>
