<?php
$dirs = [
    'c:/xampp/htdocs/akazastore/public/akaza_topup/topup/',
    'c:/xampp/htdocs/akaza_topup/topup/'
];

$files = ['ml.css', 'ff.css', 'mc.css', 'pubg.css'];

foreach ($dirs as $dir) {
    foreach ($files as $file) {
        $path = $dir . $file;
        if (file_exists($path)) {
            $content = file_get_contents($path);
            
            // Cari pattern:
            // .container {
            //     padding: 14px;
            // }
            // ubah menjadi:
            // .container {
            //     padding: 14px;
            //     padding-bottom: 100px;
            // }
            
            // Gunakan str_replace yang compatible dengan Windows (\r\n) dan Linux (\n)
            $target1 = ".container {\r\n        padding: 14px;\r\n    }";
            $target2 = ".container {\n        padding: 14px;\n    }";
            
            $replacement1 = ".container {\r\n        padding: 14px;\r\n        padding-bottom: 100px;\r\n    }";
            $replacement2 = ".container {\n        padding: 14px;\n        padding-bottom: 100px;\n    }";
            
            $new_content = str_replace($target1, $replacement1, $content);
            $new_content = str_replace($target2, $replacement2, $new_content);
            
            if ($new_content !== $content) {
                file_put_contents($path, $new_content);
                echo "Successfully updated bottom padding in $path\n";
            } else {
                echo "No replacement made or already updated in $path\n";
            }
        } else {
            echo "File not found: $path\n";
        }
    }
}
