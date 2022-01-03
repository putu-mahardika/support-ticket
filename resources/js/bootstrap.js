window._ = require('lodash');
try {
    window.Popper = require('popper.js').default;
    window.$ = window.jQuery = require('jquery');

    require('bootstrap');
} catch (e) {}

window.axios = require('axios');
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

require('datatables.net/js/jquery.dataTables.min.js');
require('datatables.net-bs4/js/dataTables.bootstrap4.min.js');

require('datatables.net-buttons/js/dataTables.buttons.min.js');
require('datatables.net-buttons/js/buttons.flash.min.js');
require('datatables.net-buttons/js/buttons.html5.min.js');
require('datatables.net-buttons/js/buttons.print.min.js');
require('datatables.net-buttons/js/buttons.colVis.min.js');

require('datatables.net-select/js/dataTables.select.min.js');

window.moment = require('moment');

window.select2 = require('select2/dist/js/select2.js');
require('dropzone');

window.Swal = require("sweetalert2/dist/sweetalert2.js");

require('devextreme/dist/js/dx.all.js');

require('lightgallery/lib/lightgallery.js');
require('lightgallery/plugins/thumbnail/lg-thumbnail.min.js');
require('lightgallery/plugins/zoom/lg-zoom.min.js');

require('@babel/polyfill/dist/polyfill.min.js');

require('exceljs/dist/exceljs.min.js');

require('jspdf');
require('jspdf-autotable/dist/jspdf.plugin.autotable.min.js');



