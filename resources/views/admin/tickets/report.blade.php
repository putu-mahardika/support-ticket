@extends('layouts.admin')
@section('content')
{{-- @can('ticket_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12 d-flex justify-content-between">
            <a class="btn btn-success" href="{{ route("admin.tickets.create") }}">
                {{ trans('global.add') }} {{ trans('cruds.ticket.title_singular') }}
            </a>
            <a class="btn btn-primary" href="{{ route("admin.tickets.showReport") }}">
                Laporan
            </a>
        </div>
    </div>
@endcan --}}
<div class="content">
  <div class="input-daterange">
    <div class="row">
        <div class="col-md-4">
            <label for="awal">awal</label>
            <input type="date" class="form-control" name="awal" id="awal">
        </div>
        <div class="col-md-4">
            <label for="akhir">akhir</label>
            <input type="date" class="form-control" name="akhir" id="akhir">
        </div>
        <div class="col-md-4">
            <button type="submit" name="filter" id="filter" class="btn btn-primary btn-sm" style="margin-top: 2.22rem; width: 120px;">Filter</button>
        </div>
    </div>
    <br>
  </div>
  <div class="card">
      <div class="card-header">
          {{ trans('cruds.ticket.title_singular') }} {{ trans('global.list') }}
          <span id="show_range"></span>

      </div>
      @if(session('status'))
          <div class="alert alert-success" role="alert">
              {{ session('status') }}
          </div>
      @endif
      <div class="card-body">
        <div class="demo-container">
            <div id="exportButton"></div>
            <div id="gridContainer"></div>
        </div>
      </div>
  </div>
</div>

@endsection
@section('scripts')
@parent
<script src="https://cdn3.devexpress.com/jslib/21.1.5/js/dx.all.js"></script>

<!-- Export Excel -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/babel-polyfill/7.4.0/polyfill.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/exceljs/4.1.1/exceljs.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.2/FileSaver.min.js"></script>
<!-- End Export Pdf -->

{{-- Export Pdf --}}
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.3.1/jspdf.umd.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.14/jspdf.plugin.autotable.min.js"></script>
{{-- End Export Pdf --}}

<script>
  getData();
  $('#filter').on('click', function () {
      let awal = $('#awal').val();
      let akhir = $('#akhir').val();
      console.log(awal);
      console.log(akhir);
      $('#show_range').html('('+awal+' - '+akhir+')');
      getData(awal, akhir);
  });

  let _token = $('meta[name="csrf-token"]').attr('content');
  window.jsPDF = window.jspdf.jsPDF;
  applyPlugin(window.jsPDF);
  function getData(awal = '', akhir = '') {
    $('#exportButton').dxButton({
        icon: 'exportpdf',
        text: 'Export to PDF',
        onClick: function() {
          const doc = new jsPDF();
          DevExpress.pdfExporter.exportDataGrid({
            jsPDFDocument: doc,
            component: grid
          }).then(function() {
            doc.save('Report Help Desk ('+awal+' - '+akhir+').pdf');
          });
        }
    });
    var grid = $("#gridContainer").dxDataGrid({
        dataSource: `{{ url('admin/tickets/getReport') }}?awal=${awal}&akhir=${akhir}`,
        data: {awal: awal, akhir: akhir},
        columns: [
          "tgl",
          "proyek",
          "author",
          "kategori",
          "prioritas",
          "judul",
          "deskripsi",
          "status",
          "work_duration"
        ],
        showBorders: true,
        filterRow: { visible: true },
        headerFilter: { visible: true },
        paging: {
            pageSize: 10
        },
        pager: {
            visible: true,
            showNavigationButtons: true,
        },
        selection: {
            mode: 'single',
            columnRenderingMode: "virtual"
        },
        export: {
            enabled: true,
            allowExportSelectedData: false
        },
        onExporting: function(e) {
          var workbook = new ExcelJS.Workbook();
          var worksheet = workbook.addWorksheet(awal+' - '+akhir);

          DevExpress.excelExporter.exportDataGrid({
            component: e.component,
            worksheet: worksheet,
            autoFilterEnabled: true
          }).then(function() {
            workbook.xlsx.writeBuffer().then(function(buffer) {
              saveAs(new Blob([buffer], { type: 'application/octet-stream' }), 'Report Help Desk ('+awal+' - '+akhir+').xlsx');
            });
          });
          e.cancel = true;
        },
    }).dxDataGrid('instance');
  };



  // $(function(){
  //   $('#exportButton').dxButton({
  //       icon: 'exportpdf',
  //       text: 'Export to PDF',
  //       onClick: function() {
  //         const doc = new jsPDF();
  //         DevExpress.pdfExporter.exportDataGrid({
  //           jsPDFDocument: doc,
  //           component: grid
  //         }).then(function() {
  //           doc.save('Report Help Desk ('+awal+' - '+akhir+').pdf');
  //         });
  //       }
  //   });
  // });
</script>
@endsection
