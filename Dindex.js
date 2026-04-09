
const sideLinks = document.querySelectorAll('.sidebar .side-menu li a:not(.logout)');

sideLinks.forEach(item => {
    const li = item.parentElement;
    item.addEventListener('click', () => {
        sideLinks.forEach(i => {
            i.parentElement.classList.remove('active');
        })
        li.classList.add('active');
    })
});


const menuBar = document.querySelector('.content nav .bx.bx-menu');
const sideBar = document.querySelector('.sidebar');

menuBar.addEventListener('click', () => {
    sideBar.classList.toggle('close');
});


const notifBtn = document.getElementById('notif-btn');

if (notifBtn) {
    notifBtn.addEventListener('click', function(e) {
        e.preventDefault(); 
        
        
        alert("Notification: Mayroon kang 12 na bagong alerts sa iyong inventory!");
        
        
        const countBadge = this.querySelector('.count');
        if (countBadge) {
            countBadge.style.display = 'none';
        }
    });
}


window.addEventListener('resize', () => {
    if (window.innerWidth < 768) {
        sideBar.classList.add('close');
    } else {
        sideBar.classList.remove('close');
    }
});

if (window.innerWidth < 768) {
    sideBar.classList.add('close');
}
// Dindex.js
const insightCards = document.querySelectorAll('.insights li');

insightCards.forEach(card => {
    card.addEventListener('click', function() {
        const category = this.querySelector('p').innerText;
        
        // Magpadala ng request sa server para sa historical data
        fetch(`get_history.php?category=${category}`)
            .then(response => response.json())
            .then(data => {
                alert(`Historical Data for ${category}: Total is ${data.total}`);
                // Dito mo pwedeng buksan ang isang Modal/Popup para ipakita ang graph
            });
    });
});