$(document).ready(function(){



  //============================================================================ DRIVER UPLOAD DRIVING LICENCE
 $(document).on('change', '#driving_licence', function(){
  var name = document.getElementById("driving_licence").files[0].name;
  var driver = document.getElementById("hdid").value;
  var form_data = new FormData();
  var ext = name.split('.').pop().toLowerCase();
  if(jQuery.inArray(ext, ['gif','png','jpg','jpeg']) == -1) 
  {
   // alert("Invalid Image File");
   $('#uploaded_driving').html("<div class='p-3 mb-2 bg-danger text-white' style='font-weight;opacity: 0.9;'>Invalid Image File</div>");
  }
  var oFReader = new FileReader();
  oFReader.readAsDataURL(document.getElementById("driving_licence").files[0]);
  var f = document.getElementById("driving_licence").files[0];
  var fsize = f.size||f.fileSize;
  if(fsize > 2000000)
  {
   // alert("Image File Size is very big");
   $('#uploaded_driving').html("<div class='p-3 mb-2 bg-danger text-white' style='font-weight;opacity: 0.9;'>Image File Size is very big</div>");
  }
  else
  {
   form_data.append("driving_licence", document.getElementById('driving_licence').files[0]);
   form_data.append("driver", driver);
   $.ajax({
    url:"main.php",
    method:"POST",
    data: form_data,
    contentType: false,
    cache: false,
    processData: false,
    beforeSend:function(){
     $('#uploaded_driving').html("<label class='text-success'>Image Uploading...</label>");
    },   
    success:function(data)
    {
      if (data=='success') {
        $('#uploaded_driving').html("<div class='p-3 mb-2 bg-success text-white' style='font-weight;opacity: 0.9;'>Driving License uploaded completely </div>");
      }else{
        $('#uploaded_driving').html("<div class='p-3 mb-2 bg-danger text-white' style='font-weight;opacity: 0.9;'>"+data+"</div>");
      }
     
    }
   });
  }
 });


  //============================================================================ DRIVER UPLOAD VEHICLE LICENCE
 $(document).on('change', '#vehicle_licence', function(){
  var name = document.getElementById("vehicle_licence").files[0].name;
  var driver = document.getElementById("hdid").value;
  var form_data = new FormData();
  var ext = name.split('.').pop().toLowerCase();
  if(jQuery.inArray(ext, ['gif','png','jpg','jpeg']) == -1) 
  {
   // alert("Invalid Image File");
   $('#uploaded_vehicle').html("<div class='p-3 mb-2 bg-danger text-white' style='font-weight;opacity: 0.9;'>Invalid Image File</div>");

  }
  var oFReader = new FileReader();
  oFReader.readAsDataURL(document.getElementById("vehicle_licence").files[0]);
  var f = document.getElementById("vehicle_licence").files[0];
  var fsize = f.size||f.fileSize;
  if(fsize > 2000000)
  {
   // alert("Image File Size is very big");
   $('#uploaded_vehicle').html("<div class='p-3 mb-2 bg-danger text-white' style='font-weight;opacity: 0.9;'>Image File Size is very big</div>");

  }
  else
  {
   form_data.append("vehicle_licence", document.getElementById('vehicle_licence').files[0]);
   form_data.append("driver", driver);
   $.ajax({
    url:"main.php",
    method:"POST",
    data: form_data,
    contentType: false,
    cache: false,
    processData: false,
    beforeSend:function(){
     $('#uploaded_vehicle').html("<label class='text-success'>Image Uploading...</label>");
    },   
    success:function(data)
    {
      if (data=='success') {
        $('#uploaded_vehicle').html("<div class='p-3 mb-2 bg-success text-white' style='font-weight;opacity: 0.9;'>Vehicle License uploaded completely </div>");
      }else{
        $('#uploaded_vehicle').html("<div class='p-3 mb-2 bg-danger text-white' style='font-weight;opacity: 0.9;'>"+data+"</div>");
      }
     
    }
   });
  }
 });

   //============================================================================ DRIVER UPLOAD INSURANCE PAPERS
 $(document).on('change', '#insurance_paper', function(){
  var name = document.getElementById("insurance_paper").files[0].name;
  var driver = document.getElementById("hdid").value;
  var form_data = new FormData();
  var ext = name.split('.').pop().toLowerCase();
  if(jQuery.inArray(ext, ['gif','png','jpg','jpeg']) == -1) 
  {
   // alert("Invalid Image File");
   $('#uploaded_insurance').html("<div class='p-3 mb-2 bg-danger text-white' style='font-weight;opacity: 0.9;'>Invalid Image File</div>");
  }
  var oFReader = new FileReader();
  oFReader.readAsDataURL(document.getElementById("insurance_paper").files[0]);
  var f = document.getElementById("insurance_paper").files[0];
  var fsize = f.size||f.fileSize;
  if(fsize > 2000000)
  {
   // alert("Image File Size is very big");
   $('#uploaded_insurance').html("<div class='p-3 mb-2 bg-danger text-white' style='font-weight;opacity: 0.9;'>Image File Size is very big</div>");
  }
  else
  {
   form_data.append("insurance_paper", document.getElementById('insurance_paper').files[0]);
   form_data.append("driver", driver);
   $.ajax({
    url:"main.php",
    method:"POST",
    data: form_data,
    contentType: false,
    cache: false,
    processData: false,
    beforeSend:function(){
     $('#uploaded_insurance').html("<label class='text-success'>Image Uploading...</label>");
    },   
    success:function(data)
    {
      if (data=='success') {
        $('#uploaded_insurance').html("<div class='p-3 mb-2 bg-success text-white' style='font-weight;opacity: 0.9;'>Insurance Document uploaded completely </div>");
      }else{
        $('#uploaded_insurance').html("<div class='p-3 mb-2 bg-danger text-white' style='font-weight;opacity: 0.9;'>"+data+"</div>");
      }
     
    }
   });
  }
 });

//==================================================================================== SAVE DRIVER CAR SEATS AND MODEL

$(document).on('click','#save', function(){
  // alert("Clicked");
  var carType = document.getElementById("cartype").value;
  var carSeats = document.getElementById("carseats").value;
  var driver = document.getElementById("hdid").value;
  if (carType!=null && carType!='' && carSeats!=null && carSeats!='') {
    $("#save").attr('disabled','disabled');
   $.ajax({
    url:"main.php",
    method:"GET",
    data: {saveCarSeatsModel:true,carType:carType,carSeats:carSeats,driver:driver},
    cache: false,
    // processData: false,
    beforeSend:function(){
     $('#uploaded_save').html("<label class='text-success'>Saving, Please wait ...</label>");
    },   
    success:function(data)
    {
      if (data == 'success') {
        $('#uploaded_save').html("<div class='p-3 mb-2 bg-success text-white' style='font-weight;opacity: 0.9;'>Your Records are submitted to AbyRide-TAXI, you'll be communicated after Document Verification.</div>");
        function closeTab(){
          location.reload();
        }
        setTimeout(closeTab, 5000);
      }else{
        $('#uploaded_save').html("<div class='p-3 mb-2 bg-danger text-white' style='font-weight;opacity: 0.9;'>"+data+"</div>");
      }
     
    }
   });

  }else{
    $('#uploaded_save').html("<div class='p-3 mb-2 bg-danger text-white' style='font-weight;opacity: 0.9;'>Please indicate car model and seats.</div>");
  }
});
});