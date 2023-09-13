// Setup
// Login https://024393460570.signin.aws.amazon.com/console 
// Create t2.large EC2 instance
sudo yum install emacs
sudo yum install java


// Create a new directory
sudo mkdir /opt/minecraft-server
cd /opt/minecraft-server/

// Download minecraft
sudo wget https://piston-data.mojang.com/v1/objects/84194a2f286ef7c14ed7ce0090dba59902951553/server.jar

// Grab details https://us-east-1.console.aws.amazon.com/ec2/home?region=us-east-1#Instances:instanceState=running 
// Allow port 25565 tcp custom ssh anywhere

// Run minecraft
sudo java -Xmx1024M -Xms1024M -jar server.jar nogui
// Accept End User Agreement and Run again
sudo emacs eula.txt 
sudo java -Xmx1024M -Xms1024M -jar server.jar nogui

// Look at Cloudwatch metrics https://us-east-1.console.aws.amazon.com/cloudwatch/home?region=us-east-1#home:dashboards/EC2 
// Look at DD dashboard https://app.datadoghq.com/dashboard/c6h-kud-4f9/minecraft-overview-with-jvm-metrics?from_ts=1687493507067&to_ts=1687497107067&live=true

// Add DD agent
sudo DD_API_KEY=$DD_API_KEY DD_SITE="datadoghq.com" bash -c "$(curl -L https://s3.amazonaws.com/dd-agent/scripts/install_script_agent7.sh)"
sudo dnf install -y libxcrypt-compat

// Add Java agent https://app.datadoghq.com/apm/service-setup?architecture=host-based&language=java&profiling=true
sudo wget -O dd-java-agent.jar 'https://dtdg.co/latest-java-tracer'

// Enable logging in config file https://app.datadoghq.com/logs/onboarding/server
sudo emacs /etc/datadog-agent/datadog.yaml

// Find where the logs are
ls /opt/minecraft-server/logs/

// Point java logs to java agent
sudo mkdir /etc/datadog-agent/conf.d/java.d
sudo emacs /etc/datadog-agent/conf.d/java.d/conf.yaml

// Restart Datadog agent https://docs.datadoghq.com/agent/basic_agent_usage/amazonlinux/?tab=agentv6v7
sudo systemctl restart datadog-agent
sudo datadog-agent status

// Rerun minecraft with java tracing
sudo java -javaagent:dd-java-agent.jar   -Ddd.profiling.enabled=true   -XX:FlightRecorderOptions=stackdepth=256   -Ddd.logs.injection=true   -Ddd.service=minecraft -Xmx1024M -Xms1024M -jar server.jar nogui

// Enable JMX monitoring and whitelist in server.properties

java -javaagent:dd-java-agent.jar \
  -Ddd.profiling.enabled=true \
  -XX:FlightRecorderOptions=stackdepth=256 \
  -Ddd.logs.injection=true \
  -Ddd.service=minecraft \
  -jar server.jar

sudo java -Dcom.sun.management.jmxremote.port=80 -Dcom.sun.management.jmxremote.authenticate=false -javaagent:dd-java-agent.jar -Ddd.profiling.enabled=true -XX:FlightRecorderOptions=stackdepth=256 -Ddd.logs.injection=true -Ddd.service=minecraft -Xmx1024M -Xms1024M -jar server.jar nogui



// September StackConf

sudo yum install java-17-amazon-corretto
wget https://download.getbukkit.org/craftbukkit/craftbukkit-1.20.1.jar
java -jar craftbukkit-1.20.1.jar
emacs eula.txt
java -jar craftbukkit-1.20.1.jar
cd plugins/
wget https://github.com/sladkoff/minecraft-prometheus-exporter/releases/download/v2.5.0/minecraft-prometheus-exporter-2.5.0.jar
java -jar craftbukkit-1.20.1.jar
curl 127.0.0.1:9225/metrics
wget https://github.com/open-telemetry/opentelemetry-collector-releases/releases/download/v0.84.0/otelcol_0.84.0_linux_amd64.rpm
sudo yum install ./otelcol_0.84.0_linux_amd64.rpm
sudo emacs /etc/otelcol/config.yaml
sudo systemctl restart otelcol


journalctl -f

  # Collect own metrics
  prometheus:
    config:
      scrape_configs:
        #      - job_name: 'otel-collector'
        #scrape_interval: 10s
        #static_configs:
        #- targets: ['0.0.0.0:8888']
      - job_name: 'minecraft'
        scrape_interval: 10s
        static_configs:
        - targets: ['127.0.0.1:9225']

// References
https://docs.datadoghq.com/opentelemetry/otel_collector_datadog_exporter/?tab=onahost
https://github.com/sladkoff/minecraft-prometheus-exporter
https://github.com/sladkoff/minecraft-prometheus-exporter
