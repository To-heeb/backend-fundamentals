<?php

echo "I am here";
putenv("CLOUDAMQP_URL=amqps://vlcnmllr:4olcUtN0a8V0qn6b67ZDjlh7-bBmZCBW@rat.rmq2.cloudamqp.com/vlcnmllr");
echo getenv("CLOUDAMQP_URL");
