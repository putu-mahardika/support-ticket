window._ = require('lodash');
try {
    window.Popper = require('popper.js').default;
    window.$ = window.jQuery = require('jquery');

    require('bootstrap');
} catch (e) {}

window.moment = require('moment');

window.select2 = require('select2/dist/js/select2.js');
window.Dropzone = require('dropzone');

window.Swal = require("sweetalert2/dist/sweetalert2.js");
window.Toast = Swal.mixin({
    toast: true,
    timer: 5000,
    position: 'top-end',
    timerProgressBar: true,
    showConfirmButton: false,
    didOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer)
        toast.addEventListener('mouseleave', Swal.resumeTimer)
    }
});

require('./dx.config');
window.PhotoSwipe = require('photoswipe');
window.PhotoSwipeUI_Default = require('photoswipe/src/js/ui/photoswipe-ui-default');

require('@babel/polyfill/dist/polyfill.min.js');
window.ExcelJS = require('exceljs');
require('file-saver');
// require('jspdf');
// require('jspdf-autotable/dist/jspdf.plugin.autotable.min.js');



