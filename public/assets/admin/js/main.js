const allSideMenu = document.querySelectorAll('#sidebar .side-menu.top li a');

allSideMenu.forEach(item => {
    const li = item.parentElement;

    item.addEventListener('click', function () {
        allSideMenu.forEach(i => {
            i.parentElement.classList.remove('active');
        })
        li.classList.add('active');
    })
});


function loadPref(key) {
    try { return localStorage.getItem(key); } catch (e) { return null; }
}
function savePref(key, value) {
    try { localStorage.setItem(key, value); } catch (e) {}
}

// TOGGLE SIDEBAR
const menuBar = document.querySelector('#content nav .bx.bx-menu');
const sidebar = document.getElementById('sidebar');

// Sidebar state: 'collapsed' | 'expanded'
function applySidebar() {
    if (window.innerWidth <= 576) {
        sidebar.classList.add('hide');   
    } else {
        sidebar.classList.toggle('hide', loadPref('sidebar') === 'collapsed');
    }
}

applySidebar();

menuBar.addEventListener('click', function () {
    sidebar.classList.toggle('hide');
    if (window.innerWidth > 576) {
        savePref('sidebar', sidebar.classList.contains('hide') ? 'collapsed' : 'expanded');
    }
});

window.addEventListener('resize', applySidebar);

const searchButton = document.querySelector('#content nav form .form-input button');
const searchButtonIcon = document.querySelector('#content nav form .form-input button .bx');
const searchForm = document.querySelector('#content nav form');

searchButton.addEventListener('click', function (e) {
    if (window.innerWidth < 768) {
        e.preventDefault();
        searchForm.classList.toggle('show');
        if (searchForm.classList.contains('show')) {
            searchButtonIcon.classList.replace('bx-search', 'bx-x');
        } else {
            searchButtonIcon.classList.replace('bx-x', 'bx-search');
        }
    }
})

// Dark Mode Switch 
const switchMode = document.getElementById('switch-mode');

if (loadPref('theme') === 'dark') {
    document.body.classList.add('dark');
    switchMode.checked = true;   
}

switchMode.addEventListener('change', function () {
    document.body.classList.toggle('dark', this.checked);
    savePref('theme', this.checked ? 'dark' : 'light');
});

// Notification Menu Toggle
document.querySelector('.notification').addEventListener('click', function () {
    document.querySelector('.notification-menu').classList.toggle('show');
    document.querySelector('.profile-menu').classList.remove('show'); 
});

// Profile Menu Toggle
document.querySelector('.profile').addEventListener('click', function () {
    document.querySelector('.profile-menu').classList.toggle('show');
    document.querySelector('.notification-menu').classList.remove('show'); 
});

// Close menus if clicked outside
window.addEventListener('click', function (e) {
    if (!e.target.closest('.notification') && !e.target.closest('.profile')) {
        document.querySelector('.notification-menu').classList.remove('show');
        document.querySelector('.profile-menu').classList.remove('show');
    }
});

    function toggleMenu(menuId) {
        var menu = document.getElementById(menuId);
        var allMenus = document.querySelectorAll('.menu');

        allMenus.forEach(function(m) {
            if (m !== menu) {
            m.style.display = 'none';
            }
        });

        if (menu.style.display === 'none' || menu.style.display === '') {
            menu.style.display = 'block';
        } else {
            menu.style.display = 'none';
        }
        }

        document.addEventListener("DOMContentLoaded", function() {
        var allMenus = document.querySelectorAll('.menu');
        allMenus.forEach(function(menu) {
            menu.style.display = 'none';
        });
    });