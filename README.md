## Cài đặt môi trường Docker cho dự án kotanglish
- [1. Khởi động nginx-proxy để kết nối Docker và Nginx với nhau](#
- [2. Khởi tạo container chưa database cho dự án](#2)1)
- [3. Cài đặt môi trường Docker cho dự án](#3)
	
<a name="1" />

### 1. Khởi động nginx-proxy để kết nối Docker và Nginx với nhau
- Tạo thư mục với tên nginx-proxy và di chuyển vào thư mục mới tạo bằng lệnh <code>cd nginx-proxy</code>
- Tiếp theo, chúng ta cần tạo một mạng Docker mà chúng ta sẽ sử dụng để kết nối tất cả các vùng chứa này với nhau.
```sh
docker network create nginx-proxy
```
- Tạo file docker-compose.yml với nội dung như sau:
```sh
	version: "3"
	services:
	  nginx-proxy:
	    image: jwilder/nginx-proxy:latest
	    container_name: nginx-proxy
	    ports:
	      - "80:80"
	    volumes:
	      - /var/run/docker.sock:/tmp/docker.sock:ro

	networks:
	  default:
	    external:
	      name: nginx-proxy
```
- <i>Lưu ý: Do chúng ta đang sử dụng cổng 80, nên để khởi động được container trên bạn cần tắt các dịch vụ hoặc các container khách đang sử dụng chung cổng 80.</i>
- Và sau đó chạy lệnh sau để bắt đầu.
```sh
docker-compose up -d
```

<a name="2" />
	
### 2.Khởi tạo container chưa database cho dự án
- Ở đây chúng ta sử dụng mysql:5.7 
- Tạo thư mục với tên mysql và di chuyển vào thư mục mới tạo bằng lệnh <code>cd mysql</code>
- Tiếp theo, chúng ta cần tạo một mạng dbshared để các container khác có thể kết nối.
```sh
docker network create dbshared
```
- Tạo file docker-compose.yml với nội dung như sau:
```sh
	version: "3.3"
	services:
	    db:
		image: mysql:5.7
		container_name: mysql
		environment:
		    MYSQL_ROOT_USER: root
		    MYSQL_ROOT_PASSWORD: abc123
		    MYSQL_DATABASE: kotanglish
		command: ['--character-set-server=utf8mb4', '--collation-server=utf8mb4_unicode_ci','--default-authentication-plugin=mysql_native_password']
		volumes:
		    - ./var/www/hmtl/mysql/db/data:/var/lib/mysql
		    - ./var/www/hmtl/mysql/db/my.cnf:/etc/mysql/conf.d/my.cnf
		ports:
		    - "3306:3306"
		networks:
		    - network_n1
	    adminer:
		image: adminer
		restart: always
		ports:
		    - "8081:8080"
		networks:
		    - network_n1
	networks:
	    network_n1:
		external: 
		    name: dbshared
```
- Và sau đó chạy lệnh sau để bắt đầu.
```sh
docker-compose up -d
```


<a name="3" />
	
### 3.Cài đặt môi trường Docker cho dự án

  <p>1. Pull code</p>
  <pre><code>git clone https://github.com/minhducita/kotanglish.git</code></pre> 
  <p>2. Di chuyển vào thư mục kotanglish</p>
  <pre><code>cd kotanglish</code></pre>    
  <p>3. Cập nhật wp-config.php</p>
  <pre><code>
    DB_NAME – tên database.
    DB_USER – tên người dùng database.
    DB_PASSWORD – password của người dùng.
    DB_HOST – hostname database.
  </code></pre>
  <p><i>hostname database (giá trị này thường là localhost, nhưng nó có thể thay đổi tùy thuộc vào nền tảng hosting của bạn).</i></p>
  
  
  
  <p>3. Tạo file docker-compose.yml và khai báo container<p>
  <p>Nội dung file docker-compose.yml bên dưới.

  Khai báo các thông số cho mysql

  <ul>
    <li>User mình chọn là <code>root</code> cụ thể trong docker-compose là <code>MYSQL_USER: root</code></li>
    <li>Password của mysql mình cũng đặt là <code>root</code> cụ thể trong docker-compose là <code>MYSQL_ROOT_PASSWORD: root</code></li>
    <li>Database name mình đặt là <code>wordpress</code> cụ thể trong file docker-compse là <code>WORDPRESS_DB_NAME: wordpress</code></li>
  </ul>

  Bạn có thể thay đổi user/pass và database name tùy ý mình nhé
  
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
  <pre><code>sudo docker-compose up -d</code></pre>












<article class="markdown-body entry-content container-lg" itemprop="text">
  <h1> Cài đặt môi trường Docker cho dự án kotanglish</h1>
  <p>Bước 1. </p>
  
  
  
  
  
  
  
  
  
  
  
  
  <p>1. Pull code</p>
  <pre><code>git clone https://github.com/minhducita/kotanglish.git</code></pre> 
  <p>2. Di chuyển vào thư mục kotanglish</p>
  <pre><code>cd kotanglish</code></pre>    
  <p>3. Cập nhật wp-config.php</p>
  <pre><code>
    DB_NAME – tên database.
    DB_USER – tên người dùng database.
    DB_PASSWORD – password của người dùng.
    DB_HOST – hostname database.
  </code></pre>
  <p><i>hostname database (giá trị này thường là localhost, nhưng nó có thể thay đổi tùy thuộc vào nền tảng hosting của bạn).</i></p>
  
  
  
  <p>3. Tạo file docker-compose.yml và khai báo container<p>
  <p>Nội dung file docker-compose.yml bên dưới.

  Khai báo các thông số cho mysql

  <ul>
    <li>User mình chọn là <code>root</code> cụ thể trong docker-compose là <code>MYSQL_USER: root</code></li>
    <li>Password của mysql mình cũng đặt là <code>root</code> cụ thể trong docker-compose là <code>MYSQL_ROOT_PASSWORD: root</code></li>
    <li>Database name mình đặt là <code>wordpress</code> cụ thể trong file docker-compse là <code>WORDPRESS_DB_NAME: wordpress</code></li>
  </ul>

  Bạn có thể thay đổi user/pass và database name tùy ý mình nhé
  
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
