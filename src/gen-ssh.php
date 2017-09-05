<<!DOCTYPE html>
<html lang="en">
    <head>
        <title></title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="css/style.css" rel="stylesheet">
    </head>
    <body>

    <?php 
      //  $genkey = `ssh-keygen -q -N place_your_passphrase_here -t rsa -f ~/.ssh/id_rsa`;
        $key =  file_get_contents("/home/fabonroa/.ssh/id_rsa.pub");
        $config = "ssh -T git@bitbucket.org";
    
    ?>
    <h1>Gen ssh result</h1>
    <pre><?php echo '$genkey'?></pre>
        <h1>Public key</h1>
    <pre><?php echo $key?></pre>
    <h1>Bitbucket config auth</h1>
    <pre><?php echo $config?></pre>
    </body>
</html>
