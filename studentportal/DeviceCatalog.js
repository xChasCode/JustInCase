document.addEventListener('DOMContentLoaded', () => {
    fetch('fragments.html')
      .then(response => {
        if (!response.ok) throw new Error('Failed to load sidebar-modals.html');
        return response.text();
      })
      .then(html => {
        console.log(html);
        // Insert entire fetched HTML inside sidebar-container
        document.getElementById('sidebar-container').innerHTML = html;
  
        // Sidebar toggle setup
        const togglemenu = document.getElementById('menu-icon');
        const sidebar = document.getElementById('sidebar');
  
        if (togglemenu && sidebar) {
          togglemenu.addEventListener('click', e => {
            e.stopPropagation();
            sidebar.classList.toggle('active');
          });
  
          document.addEventListener('click', e => {
            const isClickInside = sidebar.contains(e.target) || togglemenu.contains(e.target);
            if (!isClickInside || e.target.closest('.nav-item')) {
              sidebar.classList.remove('active');
            }
          });
        }
  
        // Notification popup toggle setup
        const notificationLink = document.querySelector('a[href="#notificationPopup"]');
        const notificationPopup = document.getElementById('notificationPopup');
  
        if (notificationLink && notificationPopup) {
          notificationLink.addEventListener('click', e => {
            e.preventDefault();
            notificationPopup.classList.toggle('hidden');
          });
  
          document.addEventListener('click', e => {
            const isInsideNotification = notificationPopup.contains(e.target) || notificationLink.contains(e.target);
            if (!isInsideNotification && !notificationPopup.classList.contains('hidden')) {
              notificationPopup.classList.add('hidden');
            }
          });
        }
      })
      .catch(err => {
        console.error(err);
        document.getElementById('sidebar-container').textContent = 'Failed to load sidebar.';
      });
  });
  
    // DROPDOWN
    function selectDropdownItem(item) {
      const dropdown = item.closest('.dropdown');
      const button = dropdown.querySelector('button');
      button.textContent = item.textContent;
    }
  
    // MODAL
    function toggleModal() {
      const modal = document.getElementById('popupModal');
      modal.style.display = (modal.style.display === 'flex') ? 'none' : 'flex';
      document.body.classList.toggle('modal-open');
    }
  
    window.onclick = function(event) {
      const modal = document.getElementById('popupModal');
      if (event.target === modal) {
        modal.classList.remove('show');
      }
    }