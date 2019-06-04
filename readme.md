# Watershed MVP 3.1

> The Cape Cod Commission developed the WatershedMVP application for professionals, municipal officials and community members in order to assist in creating the most cost-effective and efficient solutions to Cape Cod’s wastewater problem. The application is an informational resource intended to provide regional estimates for planning purposes. WatershedMVP is an initiative of the Cape Cod Commission’s Strategic Information Office (SIO). 

## Dependencies
* [Docker](https://www.docker.com/)
* [Laravel](https://laravel.com/)
* [ArcGIS Javascript API](https://developers.arcgis.com/javascript/)
* [jquery](https://jquery.com/)
* [D3js](https://d3js.org/)
* [Sequelize](http://docs.sequelizejs.com/)

## Docker Build 
Place `legacy.env` or `azure.env` from CCC Dev Team Sharepoint in the project root
Remove `legacy` or `azure` from `.env` filename
```bash
# Change working directory to project path
cd /path/to/project

# Run docker-compose to start apache/php container
# Once the container is running, navigate to localhost:8080 in your web browser
docker-compose up

# To remove local images and build cache
docker-compose down -v
docker system prune -a
```

## Manual Build 
```bash
# SSH into Apache server
ssh user@host

# CD to exposed directory
cd /var/www/html

# Pull project code into directory
git pull https://github.com/capecodcommission/watershed-mvp.git
```

## Kubernetes
```bash
# Deploy new Kubernetes config to AKS Cluster
# Please note: Run only when kubernetes-compose.yml file changes
kubectl apply -f kubernetes-compose.yml

# Enter an interactive terminal into a pod id
kubectl exec -it wmvpdev-1234567 -- /bin/bash --namespace wmvp

# To delete all services, deployments, pods, replicasets, volumes
kubectl delete daemonsets,replicasets,services,deployments,pods,rc --all --namespace wmvp

# To delete a persistent volume and claim
kubectl delete pvc wmvpdb-claim --namespace wmvp

# Start cluster admin dashboard 
az aks browse --resource-group CCC-AKSGroup --name CCC-AKS-01
```

## Seeding
In the `/db_stuff/.env` file, change the `DB_HOST` variable to either `wmvpdb` (local) or wmvpdb load balancer IP on Kubernetes (production)
```bash
# In root project directory
docker-compose up --build wmvpseeds
```