## Deployment Instructions

To deploy Phuelone to a production environment, follow these steps:

1. **Clone the Repository**:
   ```bash
   git clone https://github.com/JadeHyphen/phuelone.git
   cd phuelone
   ```

2. **Configure Environment**:
   - Update the `.env` file with production settings (e.g., database credentials, app key).

3. **Run Deployment Script**:
   ```bash
   ./deploy.sh
   ```

4. **Verify Setup**:
   - Ensure the application is accessible via the production domain.
   - Check logs in the `storage/logs` directory for any issues.

5. **Restart Server**:
   - Use the deployment script or manually restart the web server (Apache/Nginx).

6. **Monitor Application**:
   - Regularly check performance and logs to ensure smooth operation.