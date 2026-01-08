<?php
// Force output buffer to ensure no whitespace before doctype
if (ob_get_level() === 0) {
    ob_start();
}
?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <title>Doctors List - Admin Panel</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">

        <style>
            html,
            body {
                margin: 0;
                padding: 0;
                height: 100%;
                overflow: hidden;
            }

            /* Hide the iframe border */
            iframe {
                border: none;
                width: 100%;
                height: 100vh;
            }
        </style>
    </head>

    <body>
        <!-- Load the original page in an iframe with proper doctype -->
        <iframe src="{{ url('/admin/doctors') }}" title="Doctors List" allowfullscreen id="doctorFrame">
        </iframe>

        <script>
            // Ensure the parent document is in standards mode
            document.doctype = document.implementation.createDocumentType('html', '', '');

            // Add meta viewport if missing
            if (!document.querySelector('meta[name="viewport"]')) {
                var meta = document.createElement('meta');
                meta.name = 'viewport';
                meta.content = 'width=device-width, initial-scale=1.0';
                document.head.appendChild(meta);
            }

            // Force standards mode
            (function() {
                if (document.compatMode === 'BackCompat') {
                    console.warn('Document was in quirks mode, forcing standards mode');
                    // Recreate the doctype
                    var doctype = document.doctype;
                    if (!doctype || doctype.name !== 'html') {
                        var newDoctype = document.implementation.createDocumentType('html', '', '');
                        document.removeChild(doctype);
                        document.insertBefore(newDoctype, document.firstChild);
                    }
                }
            })();
        </script>
    </body>

</html>
