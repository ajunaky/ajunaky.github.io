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
sudo DD_API_KEY=456a3ae9c5d4c9047dadaa9162650c29 DD_SITE="datadoghq.com" bash -c "$(curl -L https://s3.amazonaws.com/dd-agent/scripts/install_script_agent7.sh)"
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
