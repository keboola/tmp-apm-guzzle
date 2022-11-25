<?php

echo sprintf('Received %s bytes', strlen(stream_get_contents(fopen('php://input', 'rb'))));
