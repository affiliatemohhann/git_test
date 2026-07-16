const defaultConfig = require( '@wordpress/scripts/config/webpack.config' );

const path = require( 'path' );
const fs = require( 'fs' );


function getEntries() {
	const entries = {};	
	
	const componentsDir = path.resolve( __dirname, 'src/components' );
	
	if ( fs.existsSync( componentsDir ) ) {
		for( const dir of fs.readdirSync( componentsDir ) ) {
			const possibleFiles = [
				'index.js',
				'index.jsx',
				'index.ts',
				'index.tsx',
			];
			for( const file of possibleFiles ) {
				const entry = path.resolve( componentsDir, dir, file );
				if ( fs.existsSync( entry ) ) {
					entries[ `components/${ dir }/index` ] = entry;
					break;
				}
			}
		}
	}
	

	const blocksDir = path.resolve( __dirname, 'src/blocks' );
	if ( fs.existsSync( blocksDir ) ) {
	
		const scanBlocks = ( dir, basePath = '' ) => {
			for( const item of fs.readdirSync( dir ) ) {
				const itemPath = path.resolve( dir, item );
				const stat = fs.statSync( itemPath );
				
				if ( stat.isDirectory() ) {
				
					const possibleIndexFiles = [
						'index.js',
						'index.jsx',
						'index.ts',
						'index.tsx',
					];
					
					for( const file of possibleIndexFiles ) {
						const entry = path.resolve( itemPath, file );
						if ( fs.existsSync( entry ) ) {
							const entryKey = basePath
								? `blocks/${ basePath }/${ item }/index`
								: `blocks/${ item }/index`;
							entries[ entryKey ] = entry;
							break;
						}
					}
		
					const possibleViewFiles = [
						'view.js',
						'view.jsx',
						'view.ts',
						'view.tsx',
					];
					for( const file of possibleViewFiles ) {
						const viewEntry = path.resolve( itemPath, file );
						if ( fs.existsSync( viewEntry ) ) {
							const viewEntryKey = basePath
								? `blocks/${ basePath }/${ item }/view`
								: `blocks/${ item }/view`;
							entries[ viewEntryKey ] = viewEntry;
							break;
						}
					}
					
					
					const newBasePath = basePath ? `${ basePath }/${ item }` : item;
					scanBlocks( itemPath, newBasePath );
				}
			}
		};
		
		scanBlocks( blocksDir );
	}
	

	const globalEntry = path.resolve( __dirname, 'src/index.js' );
	if ( fs.existsSync( globalEntry ) ) {
		entries.index = globalEntry;
	}
	
	
	const scssDir = path.resolve( __dirname, 'src/scss' );
	const jsWrappersDir = path.resolve( __dirname, 'src/js' );
	
	
	if ( ! fs.existsSync( jsWrappersDir ) ) {
		fs.mkdirSync( jsWrappersDir, { recursive: true } );
	}
	
	if ( fs.existsSync( scssDir ) ) {
		for( const file of fs.readdirSync( scssDir ) ) {
		
			if ( file.endsWith( '.scss' ) && ! file.startsWith( '_' ) ) {
				const jsWrapperName = file.replace( '.scss', '.js' );
				const jsWrapperPath = path.resolve(
					jsWrappersDir,
					jsWrapperName,
				);
				
			
				if ( ! fs.existsSync( jsWrapperPath ) ) {
					const wrapperContent = `// Auto-generated wrapper to compile ${ file }\nimport '../scss/${ file }';\n`;
					fs.writeFileSync( jsWrapperPath, wrapperContent );
				}
				
			
				const entryKey = `css/${ file.replace( '.scss', '' ) }`;
				entries[ entryKey ] = jsWrapperPath;
			}
		}
	}
	
	return entries;
}

class CopyPhpFilesPlugin {
	apply( compiler ) {
		compiler.hooks.afterEmit.tap( 'CopyPhpFilesPlugin', () => {
			const srcDir = path.resolve( __dirname, 'src' );
			const buildDir = path.resolve( __dirname, 'build' );
			
			const copyPhpFiles = ( dir ) => {
				if ( ! fs.existsSync( dir ) ) {
					return;
				}
				
				const items = fs.readdirSync( dir );
				for( const item of items ) {
					const itemPath = path.resolve( dir, item );
					const stat = fs.statSync( itemPath );
					
					if ( stat.isDirectory() ) {
						copyPhpFiles( itemPath );
					} else if ( item.endsWith( '.php' ) ) {
						const relativePath = path.relative( srcDir, itemPath );
						const destPath = path.resolve( buildDir, relativePath );
						const destDir = path.dirname( destPath );
						
						if ( ! fs.existsSync( destDir ) ) {
							fs.mkdirSync( destDir, { recursive: true } );
						}
						
						fs.copyFileSync( itemPath, destPath );
					}
				}
			};
		
			console.log( '[copy-php] Copying PHP files...' );

			copyPhpFiles( srcDir );

			console.log( '[copy-php] ✓ PHP files copied' );
		} );
	}
}


class CleanupScssEntriesPlugin {
	apply( compiler ) {
		compiler.hooks.afterEmit.tap(
			'CleanupScssEntriesPlugin',
			( compilation ) => {
				const outputPath = compilation.outputOptions.path;
				const cssDir = path.resolve( outputPath, 'css' );

				if ( fs.existsSync( cssDir ) ) {
					const files = fs.readdirSync( cssDir );
					let deletedCount = 0;

					files.forEach( ( file ) => {
						
						if ( file.endsWith( '.js' ) || file.endsWith( '.asset.php' ) ) {
							const filePath = path.resolve( cssDir, file );
							fs.unlinkSync( filePath );
							deletedCount++;
						}
					} );

					if ( deletedCount > 0 ) {
						console.log(
							`✓ Cleaned up ${ deletedCount } SCSS entry file(s)`,
						);
					}
				}
			},
		);
	}
}

class CleanupComponentAssetsPlugin {
	apply( compiler ) {
		compiler.hooks.afterEmit.tap(
			'CleanupComponentAssetsPlugin',
			( compilation ) => {
				const outputPath = compilation.outputOptions.path;
				const componentsDir = path.resolve( outputPath, 'components' );

				if ( fs.existsSync( componentsDir ) ) {
					let deletedCount = 0;
				
					const cleanupAssetFiles = ( dir ) => {
						const items = fs.readdirSync( dir );
						items.forEach( ( item ) => {
							const itemPath = path.resolve( dir, item );
							const stat = fs.statSync( itemPath );

							if ( stat.isDirectory() ) {
								cleanupAssetFiles( itemPath );
							} else if ( item.endsWith( '.asset.php' ) ) {
								fs.unlinkSync( itemPath );
								deletedCount++;
							}
						} );
					};

					cleanupAssetFiles( componentsDir );

					if ( deletedCount > 0 ) {
						console.log(
							`✓ Cleaned up ${ deletedCount } component asset file(s)`,
						);
					}
				}
			},
		);
	}
}


class RenameBlockCssPlugin {
	apply( compiler ) {
		compiler.hooks.compilation.tap(
			'RenameBlockCssPlugin',
			( compilation ) => {
				compilation.hooks.processAssets.tap(
					{
						name: 'RenameBlockCssPlugin',
					
						stage: compilation.PROCESS_ASSETS_STAGE_OPTIMIZE_TRANSFER,
					},
					( assets ) => {
					
						const assetsToRename = [];
						
						for( const assetName in assets ) {
							
							if (
								assetName.startsWith( 'blocks/' ) &&
								( assetName.includes( '/index.css' ) ||
									assetName.includes( '/index-rtl.css' ) )
							) {
								assetsToRename.push( assetName );
							}
						}
						
					
						for( const oldName of assetsToRename ) {
							const newName = oldName
								.replace( '/index.css', '/style.css' )
								.replace( '/index-rtl.css', '/style-rtl.css' );
						
							compilation.emitAsset( newName, assets[ oldName ] );
							
						
							compilation.deleteAsset( oldName );
						}
						
						if ( assetsToRename.length > 0 ) {
							console.log(
								`✓ Renamed ${ assetsToRename.length } block CSS files: index.css → style.css`,
							);
						}
					},
				);
			},
		);
	}
}

const entries = getEntries();

module.exports = {
	...defaultConfig,
	entry: {
        ...defaultConfig.entry(), 
        ...entries,
    },	
	output: {
		path: path.resolve( __dirname, 'build' ),
		filename: '[name].js',
		clean: true,
	},
	optimization: {
		...defaultConfig.optimization,
		splitChunks: false,
	},
	plugins: [
		...defaultConfig.plugins,
		new CopyPhpFilesPlugin(),
		new CleanupScssEntriesPlugin(),
		new CleanupComponentAssetsPlugin(),
		new RenameBlockCssPlugin(),
	],
	resolve: {
		...defaultConfig.resolve,
		alias: {
			'@': path.resolve( __dirname, './src' ),
		},
	},
};