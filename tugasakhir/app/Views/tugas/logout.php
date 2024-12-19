<?php
    if (session()->getFlashdata('successlogout')) {
        $temp = session()->getFlashdata('successlogout');
        echo "<script>
                alert('$temp');
                window.location.href = '/home';
              </script>";
        exit();
    }
?>
