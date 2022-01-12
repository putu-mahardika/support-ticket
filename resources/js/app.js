require('./bootstrap');
require('./mqtt');
require('./sb-admin-2');
window.pswpElement = document.querySelectorAll('.pswp')[0];
window.pswpGalery = null;
window.strLimit = (text, limit, appendWith = '...') => {
    return `${text.substr(0, limit)}${text.length > 15 ? appendWith : ''}`;
}
require('./profile-picture');
