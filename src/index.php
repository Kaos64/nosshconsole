<?php
include 'password.php';
?>
<?php 
// Root server folder : 
$composer_root = str_replace('\\','/',getcwd());
$root = dirname ($composer_root);
?>

<!DOCTYPE html>
<html>
    <head>
        <title>NoConsoleComposer</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <script type="text/javascript">
            $(document).ready(function(){
                check();
            });
            function url()
            {
                return 'main.php';
            }
            function call(func)
            {
                _call(  {
                            "path":$("#path").val(),
                            "command":func,
                            "function": "command"
                        }
                );
            }
            function bb_composer(func){
                _call(  {
                            "path":$("#bb-root").val() + '/' + $("#bb-project").val(),
                            "command":func,
                            "function": "command"
                        }
                );
            }
            function bb_call(func)
            {
                _call(
                    {
                        "command":func,
                        "function": "bitbucket",
                        "path": $("#bb-root").val(),
                        "project": $("#bb-project").val(),
                        "username": $("#bb-username").val(),
                        "password": $("#bb-password").val(),
                    }
                );
            }
            function _call( params){
                $("#output").append("\nplease wait...\n");
                $("#output").append("\n===================================================================\n");
                $("#output").append("Executing Started");
                $("#output").append("\n===================================================================\n");
                $.post('main.php', params,
                function(data)
                {
                    $("#output").append(data);
                    $("#output").append("\n===================================================================\n");
                    $("#output").append("Execution Ended");
                    $("#output").append("\n===================================================================\n");
                }
                );
            }
            function check()
            {
                $("#output").append('\nloading...\n');
                $.post(url(),
                        {
                            "function": "getStatus",
                            "password": $("#password").val()
                        },
                function(data) {
                    if (data.composer_extracted)
                    {
                        $("#output").html("Ready. All commands are available.\n");
                        $("button").removeClass('disabled');
                    }
                    else if(data.composer)
                    {
                        $.post(url(),
                                {
                                    "password": $("#password").val(),
                                    "function": "extractComposer",
                                },
                                function(data) {
                                    $("#output").append(data);
                                    window.location.reload();
                                }, 'text');
                    }
                    else
                    {
                        $("#output").html("Please wait till composer is being installed...\n");
                        $.post(url(),
                                {
                                    "password": $("#password").val(),
                                    "function": "downloadComposer",
                                },
                                function(data) {
                                    $("#output").append(data);
                                    check();
                                }, 'text');
                    }
                });
            }
            function consoleClean(){
                $("#output").html('');
            }
        </script>
        <style>
            #output
            {
                width:100%;
                height:350px;
                overflow-y:scroll;
                overflow-x:hidden;
            }
        </style>
    </head>
    <body>
        <h1 style="text-align: center;">No SSH server installation</h1>
        <div class="row">
                <div class="col-lg-1"></div>
                <div class="col-lg-10">
                    <ul class="nav nav-pills  nav-justified">
                        <li class="active"><a data-toggle="tab" href="#composer">Composer</a></li>
                        <li><a data-toggle="tab" href="#bitbucket">Bitbucket</a></li>
                        <li><a data-toggle="tab" href="#phpinfo">Server information</a></li>
                    </ul>
                </div>
        </div>

        
        <div class="row">
            <div class="col-lg-1"></div>
            <div class="col-lg-10">
                <div class="tab-content">
                    <div id="composer" class="tab-pane fade in active">
                        <h3>Commands:</h3>
                        <div class="form-inline">
                            <button id="self-update" onclick="del()" class="btn btn-success disabled">Update Composer</button><br /><br />
                            <input type="text" id="path" style="width:300px;" class="form-control disabled" placeholder="absolute path to project directory" value="<?php echo $composer_root?>"/>
                            <button id="install" onclick="call('install')" class="btn btn-success disabled">install</button>
                            <button id="update" onclick="call('update')" class="btn btn-success disabled">update</button>
                            <button id="update" onclick="call('dump-autoload')" class="btn btn-success disabled">dump-autoload</button>
                        </div>
                        <h4>Composer Path : <?php echo $composer_root?> </h4>
                    </div>

                <div id="bitbucket" class="tab-pane fade">
                    <h3>Parameters :</h3>
                    <div class="form-horizontal">
                        <div class="form-group">
                            <input type="text" id="bb-root" style="width:300px;" class="form-control disabled" value="<?php echo $root ?>" placeholder="Absolute root path of project parent directory"/>
                        </div>
                        <div class="form-group">
                            <input type="text" id="bb-project" style="width:300px;"  class="form-control disabled" placeholder="Bitbucket Project name"/>
                        </div>
                        <div class="form-group">
                            <input type="text" id="bb-username" style="width:300px;" class="form-control disabled" placeholder="Bitbucket user name"/>
                        </div>
                        <div class="form-group">
                            <input type="password" id="bb-password" style="width:300px;" class="form-control disabled" placeholder="Bitbucket user password"/>
                        </div>
                    </div>
                    <hr/>

                    <h3>Commands:</h3>
                    <div class="form-inline">
                        <button id="clone" onclick="bb_call('clone')" class="btn btn-success disabled">BB - Clone</button>
                        <button id="pull" onclick="bb_call('pull')" class="btn btn-success disabled">BB - Pull</button>
                        <button id="install" onclick="bb_composer('install')" class="btn btn-success disabled">Composer install</button>
                        <button id="update" onclick="bb_composer('update')" class="btn btn-success disabled">Composer update</button>
                    </div>
                </div>
                <div id="phpinfo" class="tab-pane fade">
                    <iframe src="phpinfo.php" width="100%" height="700px" >
                    </iframe>
                </div>
            <div class="col-lg-1"></div>
        </div>


        <div class="row">
            <div class="col-lg-1"></div>
            <div class="col-lg-10">
                
                <h3>Console Output:</h3>
                
                <pre id="output" class="well"></pre>
                <div class="form-inline">
                    <button id="clean-console" onclick="consoleClean()" class="btn btn-warning disabled">Clean console</button>
                </div>
            </div>
            <div class="col-lg-1"></div>
            
        </div>
    </body>
</html>
