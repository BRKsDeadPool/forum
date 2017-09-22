let mix = require('laravel-mix');
let qrcode = require('qrcode');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/assets/js/app.js', 'public/js')
  .sass('resources/assets/sass/app.scss', 'public/css')
  .disableNotifications()
  .sourceMaps()
  .then(() => {
    require('dns').lookup(require('os').hostname(), function (err, add, fam) {
      qrcode.toString(`http://${add}`, {
        scale: 10,
        type: 'terminal'
      }, (err, url) => {
        if (err) throw err

        process.stdout.write(url)
        process.stdout.write('\n')
      })
    })
  });
;
