window._ = require('lodash');
try {
    window.Popper = require('popper.js').default;
    window.$ = window.jQuery = require('jquery');

    require('bootstrap');
} catch (e) {}

window.axios = require('axios');
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

require('perfect-scrollbar/dist/perfect-scrollbar.min.js');
require('@coreui/coreui/dist/js/coreui.min.js');
require('jszip');


require('bootstrap4-datetimepicker/src/js/bootstrap-datetimepicker.js');
require('bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js');

require('datatables.net/js/jquery.dataTables.min.js');

require('datatables.net-bs4/js/dataTables.bootstrap4.min.js');

require('datatables.net-buttons/js/dataTables.buttons.min.js');
require('datatables.net-buttons/js/buttons.flash.min.js');
require('datatables.net-buttons/js/buttons.html5.min.js');
require('datatables.net-buttons/js/buttons.print.min.js');
require('datatables.net-buttons/js/buttons.colVis.min.js');

require('pdfmake-browserified-0.1.18/build/bundle.js');
require('pdfmake-browserified-0.1.18/vfs-fonts/roboto.js');

// require('pdfmake-font-generator');

require('datatables.net-select/js/dataTables.select.min.js');

require('@ckeditor/ckeditor5-build-classic/build/ckeditor.js');

window.moment = require('moment');

require('select2/dist/js/select2.full.min.js');

require('dropzone/src/dropzone.js');

window.Swal = require("sweetalert2/dist/sweetalert2.js");

require('devextreme/dist/js/dx.all.js');


require('lightgallery/lib/lightgallery.js');
require('lightgallery/plugins/thumbnail/lg-thumbnail.min.js');
require('lightgallery/plugins/zoom/lg-zoom.min.js');

require('@babel/polyfill/dist/polyfill.min.js');

require('exceljs/dist/exceljs.min.js');

require('file-saver/dist/FileSaver.min.js');

require('jspdf');
require('jspdf-autotable/dist/jspdf.plugin.autotable.min.js');



