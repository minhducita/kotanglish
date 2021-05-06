<article class="markdown-body entry-content container-lg" itemprop="text">
  <h1> Cài đặt môi trường Docker cho dự án kotanglish (wordpress)</h1>
  <p>1. Pull code</p>
  <pre><code>git clone https://github.com/minhducita/kotanglish.git</code></pre> 
  <p>2. Di chuyển vào thư mục kotanglish</p>
  <pre><code>cd kotanglish</code></pre> 
  <p>3. Tạo file docker-compose.yml và khai báo container<p>
  <pre><code>
    version: "3.3"
    services:
        app:
            ports:
                - 8082:80
            image: wordpress:php7.3
            container_name: kotanglish
            volumes:
                - ./web/:/var/www/html
            networks:
                - network_nginx_proxy
                - network_2
            restart: always
            environment: 
                VIRTUAL_HOST: "local.kotanglish"
    networks:
        network_nginx_proxy:
            external: 
                name: nginx-proxy
        network_2:
            external: 
                name: dbshared
  </code></pre>
  
  
  <p>Và chúng ta sẽ chạy lên thử với câu lệnh</p>
  <pre><code>sudo docker-compose up -d --build</code></pre>
  
  <p>ssh vào docker app</p>
  <pre><code>sudo docker-compose exec app bash</code></pre>
  
  <p>Chạy composer để cài đặt các gói cần thiết trong dự án laravel</p>
  <pre><code>composer install</code></pre> 
  

  <p>Thiết lập quyền lưu trữ cho storage</p>
  <pre><code>chown -R root:www-data storage/</code></pre>
  <p>Tạo khóa laravel</p>
  <pre><code>php artisan key:generate</code></pre>

  <p>Tạo cơ sở dữ liệu</p>
  <pre><code>php artisan migrate --seed</code></pre>

  <p>Tiếp đến, truy cập vào địa chỉ http://http://0.0.0.0:8080, để tận hưởng thành quả nào.</p>
  <hr>
  <p>Bạn có thể tham khảo cách tạo Docker compose tại <a href="https://kipalog.com/posts/Cai-dat-moi-truong-Docker-cho-Laravel-2019" rel="nofollow">https://kipalog.com/posts/Cai-dat-moi-truong-Docker-cho-Laravel-2019</a></p>
</article>
