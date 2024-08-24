import { createClient } from "@sanity/client";

const client = createClient({
  projectId: process.env.VITE_SANITY_PROJECT_ID, // replace with your actual project ID if not using .env
  dataset: process.env.VITE_SANITY_DATASET, // replace with your actual dataset name if not using .env
  apiVersion: "2023-08-23", // use a UTC date string for the API version
  useCdn: true, // `false` if you want to ensure fresh data
});

export default client;
