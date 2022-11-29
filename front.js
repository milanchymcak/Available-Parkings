// Global
let typingTimer;

/**
 * Search
 */
window.addEventListener('load', () => {
    const input = document.querySelector('.search input');
    if(input !== undefined && input !== null) {
        input.addEventListener('keyup', (e) => {

            // Hide/show parking spots based on search
            // Add fake latency to not fire up for every key press
            clearTimeout(typingTimer);
            typingTimer = setTimeout(searchParkingSpots(e.target.value), 1500);

        })
    }
})

/**
 * Show IP Change Form
 */
const ipChangeShow = () => {
    const ipForm = document.querySelector(".visitorIP form");
    if(ipForm !== undefined && ipForm !== null) {
        ipForm.classList.toggle("hide");
    }
}

/**
 * Search Parking Spots By Keyword
 */
const searchParkingSpots = (searchVal) => {
    searchVal = searchVal.toLowerCase();
    const parkingSpots = document.querySelectorAll('.parkingSpot');
    if(parkingSpots !== undefined && parkingSpots !== null) {

        parkingSpots.forEach(el => {

            // Hide
            if(!el.textContent.toLowerCase().includes(searchVal)) {
                if(el.classList.contains("show")) el.classList.remove("show");
                el.classList.add("hide");
            }

            // Show
            if(el.textContent.toLowerCase().includes(searchVal)) {
                if(el.classList.contains("hide")) el.classList.remove("hide");
                el.classList.add("show");
            }
        });
    }
}

/**
 * Sort parking spots
 */
const sortParkingSpots = (sortType) => {
    sortType = parseInt(sortType);
    const parkingSpots = document.querySelectorAll('.parkingSpot');
    if(parkingSpots !== undefined && parkingSpots !== null) {

        Array.from(parkingSpots).sort( function(a,b){

            // By Available Spots
            if(sortType === 1 || sortType === 2) {
                a = new Date(parseInt(a.getAttribute("data-spots")))
                b = new Date(parseInt(b.getAttribute("data-spots")))
            }

            // By Distance
            if(sortType === 3 || sortType === 4) {
                a = new Date(parseInt(a.getAttribute("data-distance")))
                b = new Date(parseInt(b.getAttribute("data-distance")))
            }

            // Most available spots
            if(sortType === 1 || sortType === 4) return b - a;

            // Least available spots
            if(sortType === 2 || sortType === 3) return a - b;

        }).forEach(el => {
            document.querySelector('.container.parkingSpotsBox').appendChild(el);
        });
    }
}
