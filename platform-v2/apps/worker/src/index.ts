function bootWorker() {
  const startedAt = new Date().toISOString();
  // Phase A placeholder: RabbitMQ consumers and outbox dispatcher will be initialized here.
  // Keep process alive for local baseline.
  // eslint-disable-next-line no-console
  console.log(`[worker] IAtechs V2 worker online at ${startedAt}`);
}

bootWorker();
