document.addEventListener("DOMContentLoaded", function() {
    fetch('assets/api.php') 
        .then(response => response.json())
        .then(data => {
            data.forEach(event => {
                const card = document.createElement('div');
                card.className = 'card my-3';

                const dateObject = new Date(event.date_time);

                const formattedDate = new Intl.DateTimeFormat('en-PH', {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric',
                    hour: 'numeric',
                    minute: 'numeric',
                    hour12: true
                }).format(dateObject);
                
                console.log(formattedDate);

                const imageSource = event.image ? event.image : 'https://cdn.head-fi.org/assets/classifieds/hf-classifieds_no-image-available_2.jpg';

                card.innerHTML = `
                <h1 class="mx-auto fw-semibold mt-4">${event.title}</h1>
                <img src="${imageSource}" class="px-5" style="height: 40vh; object-fit: cover; object-position: 0 50%;" alt="...">
        
                <div class="card-body text-center">
                    <h5 class="card-text">${event.description}</h5>
                    <p class="card-text">${formattedDate}</p>
                        <p class="card-title mx-auto text-secondary">${event.event_manager}</p>
                        <a href="#" class="btn btn-sm btn-primary px-5">Join</a>
                    </div>
                `;

                // Append the card to the container
                document.getElementById('eventDetails').appendChild(card);
            });
        })
        .catch(error => console.error('Error fetching data:', error));
});

