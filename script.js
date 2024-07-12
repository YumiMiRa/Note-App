document.addEventListener('DOMContentLoaded', function() {
    const welcomeContainer = document.getElementById('container');
    const notesContainer = document.getElementById('notesContainer');
    const startBtn = document.getElementById('startBtn');
    const backBtn = document.getElementById('backBtn');
    const addNoteBtn = document.getElementById('addNoteBtn');
    const noteInput = document.getElementById('noteInput');
    const graphicContainer = document.getElementById('graphic');
    const notesList = document.getElementById('notesList');

    // Function to get current day and time
    function getCurrentDayAndTime() {
        const now = new Date();
        const days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        const day = days[now.getDay()];
        const hours = now.getHours();
        const minutes = now.getMinutes().toString().padStart(2, '0');
        return { day, hours, minutes };
    }

    // Function to get color for current time
    function getColorForCurrentTime() {
        const { day, hours, minutes } = getCurrentDayAndTime();
        const time = parseFloat(`${hours}.${minutes}`);

        if (!colorRanges.hasOwnProperty(day)) {
            return '#FFFFFF'; // Default color if day is not found
        }

        const ranges = colorRanges[day];
        for (const range of ranges) {
            if (time >= range.start && time <= range.end) {
                return range.color;
            }
        }

        return '#FFFFFF'; // Default color if time range not found
    }

    // Function to update SVG graphic
    function updateSVGGraphic() {
        const { day, hours, minutes } = getCurrentDayAndTime();
        const color = getColorForCurrentTime();
        const svg = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
        svg.setAttribute('width', '100');
        svg.setAttribute('height', '100');

        const rect = document.createElementNS('http://www.w3.org/2000/svg', 'rect');
        rect.setAttribute('x', '10');
        rect.setAttribute('y', '10');
        rect.setAttribute('width', '80');
        rect.setAttribute('height', '80');
        rect.setAttribute('fill', color);

        svg.appendChild(rect);
        graphicContainer.innerHTML = ''; // Clear previous graphic
        graphicContainer.appendChild(svg);
    }

    // Function to add a new note to the list
    function addNoteToUI(noteText, dateTime, color) {
        const note = `<div class="note" style="border-color: ${color};">
                        <p class="note-text">${noteText}</p>
                        <p class="note-datetime">${dateTime}</p>
                     </div>`;
        notesList.innerHTML += note;
    }

    // Start button click event
    startBtn.addEventListener('click', function() {
        welcomeContainer.style.display = 'none';
        notesContainer.style.display = 'block';
        updateSVGGraphic(); // Update SVG graphic when switching to notes container
    });

    // Back button click event
    backBtn.addEventListener('click', function() {
        notesContainer.style.display = 'none';
        welcomeContainer.style.display = 'block';
    });

    // Add note button click event
    addNoteBtn.addEventListener('click', function() {
        const noteText = noteInput.value.trim();
        if (noteText !== '') {
            const { day, hours, minutes } = getCurrentDayAndTime();
            const time = `${hours}:${minutes}`;
            const dateTime = `${day} - ${time}`;
            const color = getColorForCurrentTime();
            
            addNoteToUI(noteText, dateTime, color); // Add note to UI
            noteInput.value = '';
            updateSVGGraphic(); // Update SVG graphic after adding a new note
        }
    });
});
