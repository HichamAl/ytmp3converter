<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>YouTube to MP3 Converter</title>
        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
        <!-- Optional jQuery -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <style>
            body {
                background: linear-gradient(120deg, #3498db, #8e44ad);
                height: 100vh;
                display: flex;
                justify-content: center;
                align-items: center;
                color: white;
            }
            .converter-box {
                background-color: rgba(255, 255, 255, 0.1);
                padding: 30px;
                border-radius: 10px;
                text-align: center;
                box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.2);
            }
            input {
                color: black;
            }
            .btn-convert {
                background-color: #ff4757;
                border: none;
            }
            .btn-convert:hover {
                background-color: #ff6b81;
            }
        </style>
    </head>
    
    <body>
        <div class="container">
            <div class="converter-box">
                <h1>YouTube to MP3 Converter</h1>
                <p class="lead">Convert your favorite YouTube videos to MP3 in seconds.</p>
                <form class="form-inline">
                    <div class="mb-3">
                        <input id="input" type="text" class="form-control form-control-lg" name="video" placeholder="Enter YouTube URL here" aria-label="YouTube URL">
                    </div>
                    <div>
                        <button id="convert" type="submit" class="btn btn-convert btn-lg" data-ok="1">Convert</button>
                    </div>
                </form>
                <div id="mp3-dl" class="mt-4"></div>
            </div>
        </div>
    
        <script>
            function ytVidId(url) {
                var p = /((http|https)\:\/\/)?(?:[0-9A-Z-]+\.)?(?:youtu\.be\/|youtube(?:-nocookie)?\.com\S*[^\w\s-])([\w-]{11})(?=[^\w-]|$)(?![?=&+%\w.-]*(?:['"][^<>]*>|<\/a>))[?=&+%\w.-]*/ig;
                return (url.match(p)) ? RegExp.$3 : false;
            }
    
            $(document).on('click', '#convert', function(e) {
                e.preventDefault();
                $('#mp3-dl').text('Generating Links...');
                var ok = $(this).attr("data-ok");
                var url = document.getElementById("input").value;
                var ytid = ytVidId(url);
                if(ok == '1'){
                    if(ytid){
                        $('#convert').attr("data-ok", "0");
                        mp3Conversion(ytid);
                        $('#convert').attr("data-ok", "1");
                    }else{
                        $('#mp3-dl').text('Invalid Input');
                    }
                }   
            });
    
            function mp3Conversion(id){
                $.ajax({
                    type: 'GET',
                    url: '{{ route("convert") }}',
                    data: {'id': id},
                    success: function(data){
                        if(data.status == "ok") {
                            var dlink = data.link + '&dom=Iframe';
                            $("body").append('<iframe src="' + dlink + '" style="display: none;" ></iframe>');
                            $("#mp3-dl").html('<a class="btn btn-success" href="' + dlink + '">Download MP3 - ' + data.title + '</a>');
                        } else if (data.status == "processing"){
                            if(data.progress){
                                if(parseInt(data.progress) < 10){
                                    $("#mp3-dl").text('Converting ' + '10%'); 
                                }else{
                                    $("#mp3-dl").text('Converting ' + data.progress+'%');
                                }
                            }
                            setTimeout(function(){mp3Conversion(id);}, 2000);
                        } else {
                            $('#mp3-dl').text('Download Error !');
                        }
                    }
                });
            }
        </script>
    </body>
    
</html>
