try {
	// activate event
	self.addEventListener('activate', e => {
		//alert('activate')
	})
	
	// fetch event
	self.addEventListener('fetch', e => {
		//alert('fetch')
	})
	
	// install event
	self.addEventListener('install', e => {
		var sender = ( event.ports && event.ports[0] ) || event.source;
			//window.addEventListener('beforeinstallprompt', e => {
			e.prompt();
		})
	})
} catch (e) {
	
}