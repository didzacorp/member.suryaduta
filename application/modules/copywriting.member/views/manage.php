<script type="text/javascript">
$(function () {
        hideProgres();
        pageLoadCopy(1);
        $('#MemberName').html('');
  });
  function pageLoadCopy(pg)
  { 
    showProgres();
    $.post(base_url(1)+'/copywriting.member/manage/page/'+pg
      ,$('#filter_content').serialize()
      ,function(result) {
        $('#resultContent').html(result);
        hideProgres();
      }         
      ,"html"
    );
  }

  function copyDetail(id)
  { 
    showProgres();
    $.post(base_url(1)+'/copywriting.member/manage/copyDetail'
      ,{
        id :id
      }
      ,function(result) {
        $('#resultContent').hide();
        $('#resultContentDetail').show();
        $('#resultContentDetail').html(result);
        hideProgres();
      }         
      ,"html"
    );
  }

    function backToList() {
       $('#resultContent').show();
        $('#resultContentDetail').hide();
        pageLoadTraining(1);
  }

  
</script>
<div class="content-wrapper">
  <div>
    <div id="resultContent">
      
    </div>
    <div id="resultContentDetail">
      
    </div>
  </div>
</div>