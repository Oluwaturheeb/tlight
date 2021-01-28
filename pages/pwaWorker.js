try {
	// activate event
	self.addEventListener('activate', e => {
		console.log('activate')
	})
	
	// fetch event
	self.addEventListener('fetch', e => {
		console.log('fetch')
	})
	
	// install event
	self.addEventListener('install', e => {
		console.log('install');
		var sender = ( event.ports && event.ports[0] ) || event.source;
		self.addEventListener('beforeinstallprompt', e => {
			e.prompt();
		})
	})
} catch (e) {
	
}