<script type="text/javascript">
    $(function () {
        $("#main-header").hide();
        $("#main-footer").hide();
    });     

    function Login() {
        // showProgres();
        $.post(site_url+'users.login/manage/Login'
            ,$('#loginForm').serialize()
            ,function(result) {
            	 if (result.error) {
		            showDangerToast(result.error);
		            // refreshCart();
		        }else{
		            // showSuccessToast(result.message);
		            window.location.href = base_url(1)+'/member';
		        }
            }                   
            ,"json"
        );
    }
</script>   
      <div class="content-wrapper d-flex align-items-center auth login-full-bg">
        <div class="row w-100">
          <div class="col-lg-4 mx-auto">
            <div class="auth-form-dark text-left p-5">
              <h2>Login</h2>
              <h4 class="font-weight-light">Hello! let's get started Suryaduta</h4>
              <form class="pt-5" id="loginForm">
                <div class="form-group">
                  <label for="exampleInputEmail1">Email</label>
                   <input type="email" name="email" id="email" class="form-control" placeholder="Enter Email">
                  <i class="mdi mdi-account"></i>
                </div>
                <div class="form-group">
                  <label for="exampleInputPassword1">Password</label>
                   <input type="password" name="password" id="password" class="form-control" placeholder="Password" onkeydown="if(event.keyCode == 13){Login();}">
                  <i class="mdi mdi-eye"></i>
                </div>
                <div class="mt-5">
                  <a class="btn btn-block btn-warning btn-lg font-weight-medium" href="javascript:void(0)"  onclick="Login()" >Login</a>
                </div>
                <!-- <div class="mt-3 text-center">
                  <a href="#" class="auth-link text-white">Forgot password?</a>
                </div> -->
              </form>
            </div>
          </div>
        </div>
      </div>

<!--      <form action='<?php  ?>' method="post" id="memberPage">
     </form>
 -->