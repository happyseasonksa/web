<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Email Verification!</title>
      <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@400;600;700&amp;display=swap" rel="stylesheet" />
      <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" />
      <style type="text/css">*{margin:0;padding:0; font-family: 'Nunito Sans', sans-serif; color:#252735;}
      </style>
   </head>
   <body>
      <div class="order-place-body" style="max-width:900px; margin:30px auto; box-shadow: 0 0 20px rgba(0,0,0,0.1); border-radius: 10px; border: 1px solid rgba(0,0,0,0.1);">
      <header style="display:flex; align-items: center; padding: 5px 20px;">
         <div class="logo">
            <div><img alt="nadil" src="{{ asset('dist/img/happySeasonsLogo.jpeg') }}" style="width:40px;height:40px;" /></div>
         </div>
         <div class="store-link" style="margin-left:auto;"><a href="#" style=" color:#252735; text-decoration: none;">Happy Seasons </a></div>
      </header>
      <div class="content" style="background:rgb(253 41 75); padding:20px;">
         <div class="content-box" style="background-color:#fff; padding: 30px 20px; box-shadow: 0 0 10px rgba(0,0,0,0.1); border-radius: 5px;">
            <div class="thankyou-col">
               <h1 class="heading" style="color: #e60024; font-weight: 700; font-size:30px; margin-bottom: 10px;">Email Invitation!</h1>
               <div class="order-summary" style="border-top: 1px solid rgba(0,0,0,0.1);  margin-top: 30px; padding-top: 10px;">
                  <div style="overflow-x: auto; -webkit-overflow-scrolling:touch; width:100%; display:block;">
                     <p style="font-weight:bold; font-size:14px;">You received an invitation.</p>
                  </div>
               </div>
               <div class="disclaimer" style="border-top: 1px solid rgba(0,0,0,0.1); margin-top: 30px; padding-top: 10px;">
                  <p style="font-size:14px;">
                      You’ve been invited to join a Weeding. You can use it to raise requests and get help. To set up your account.
                      <br>
                      <br>
                      <br>

                      <strong>
                      Regards,<br>
                      The Happy Seasons team
                      </strong>
                  </p>
               </div>
               <footer style="margin-top: 50px; border-top: 1px solid rgba(0,0,0,0.2); display:flex; font-size:14px; padding:10px 0 0;">
                  <div class="app">
                     <p>Get the App:</p>
                     <div><a href="#"><img alt="" src="{{asset('dist/img/ios.png')}}" /></a><a href="#"><img alt="" src="{{asset('dist/img/android.png')}}" /></a></div>
                  </div>
               </footer>
            </div>
         </div>
      </div>
   </body>
</html>
