<?php $this->view('partials/head'); ?>

<div class="container">
  <div class="row">
    <div class="col-lg-12">
        <div id="audit_mr-table-view" class="row pt-4" style="padding-left: 15px; padding-right: 15px;">
          <h3><span data-i18n="audit_mr.audit_log"></span></h3>
              <table class="table table-striped table-condensed table-bordered" id="audit_mr-table">
                <thead>
                  <tr>
                    <th data-i18n="username" data-colname='audit_mr.username'></th>
                    <th data-i18n="audit_mr.timestamp" data-colname='audit_mr.timestamp'></th>
                    <th data-i18n="audit_mr.ip_address" data-colname='audit_mr.ip_address'></th>
                    <th data-i18n="audit_mr.action" data-colname='audit_mr.action'></th>
                    <th data-i18n="audit_mr.role" data-colname='audit_mr.role'></th>
                    <th data-i18n="audit_mr.user_agent" data-colname='audit_mr.user_agent'></th>
                  </tr>
                </thead>
                <tbody>
                    <tr>
                        <td data-i18n="listing.loading" colspan="6" class="dataTables_empty"></td>
                    </tr>
                </tbody>
              </table>
        </div>
    </div>
  </div>
</div>


<script>
    $(document).on('appReady', function(e, lang) {

        // Get audit history data
        $.getJSON( appUrl + '/module/audit_mr/get_data_admin', function( data ) {

            $('#audit_mr-table').DataTable({
                data: data,
                order: [[1,'asc']],
                autoWidth: false,
                columns: [
                    { data: 'username' },
                    { data: 'timestamp' },
                    { data: 'ip_address' },
                    { data: 'action' },
                    { data: 'role' },
                    { data: 'user_agent' }
                ],
                createdRow: function( nRow, aData, iDataIndex ) {
                   // Format timestamp
                    var checkin = parseInt($('td:eq(1)', nRow).html());
                    var date = new Date(checkin * 1000);
                    $('td:eq(1)', nRow).html('<span title='+moment(date).fromNow()+'">'+moment(date).format('llll')+'</span>');

                    // Format Action
                    var colvar=$('td:eq(3)', nRow).html();
                    colvar = colvar == 'Login' ? '<span class="label label-success">'+i18n.t('audit_mr.login')+'</span>' :
                    colvar = colvar == 'Login Failed' ? '<span class="label label-danger">'+i18n.t('audit_mr.login_failed')+'</span>' :
                    colvar = colvar == 'Unauthorized' ? '<span class="label label-danger">'+i18n.t('audit_mr.unauthorized')+'</span>' :
                    (colvar === 'Logout' ? '<span class="label label-info">'+i18n.t('audit_mr.logout')+'</span>' : '')
                    $('td:eq(3)', nRow).html(colvar);

                    // Format Role
                    var colvar=$('td:eq(4)', nRow).html();
                    colvar = colvar == 'admin' ? '<span class="label label-info">'+i18n.t('audit_mr.admin')+'</span>' :
                    colvar = colvar == 'user' ? '<span class="label label-success">'+i18n.t('audit_mr.user')+'</span>' :
                    colvar = colvar == 'manager' ? '<span class="label label-warning">'+i18n.t('audit_mr.manager')+'</span>' :
                    (colvar === 'archiver' ? '<span class="label label-warning">'+i18n.t('audit_mr.archiver')+'</span>' : '')
                    $('td:eq(4)', nRow).html(colvar);
                }
            });
        });
    });
</script>

<?php $this->view('partials/foot'); ?>
