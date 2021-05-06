## Cài đặt môi trường Docker cho dự án kotanglish
- [1. Khởi động nginx-proxy để kết nối Docker và Nginx với nhau](#1)
- [2. Khởi tạo container chưa database cho dự án](#2)
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

  <p>3.1. Pull code</p>
  <pre><code>git clone https://github.com/minhducita/kotanglish.git</code></pre> 
  <p>3.2. Di chuyển vào thư mục kotanglish</p>
  <pre><code>cd kotanglish</code></pre>    
  <p>3.3. Cập nhật wp-config.php</p>
  <pre><code>
   	define('DB_NAME', 'kotanglish');
	define('DB_USER', 'root');
	define('DB_PASSWORD', 'abc123');
	define('DB_HOST', 'db');
  </code></pre>
  <p>3.4. Tạo file docker-compose.yml với nội dung bên dưới<p>
  
  <pre><code>
    version: "3.3"
    services:
        app:
            ports:
                - 8082:80
            image: wordpress:php7.3
            container_name: kotanglish
            volumes:
                - ./:/var/www/html
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
  
  
  <p>Sau đó khởi động container bằng lệnh</p>
  <pre><code>docker-compose up -d</code></pre>
  
  - Tiếp theo chúng ta cấu hình file hosts. Bạn mở file hosts nằm trong thư mục có đường dẫn sau:
<pre><code>C:\Windows\System32\drivers\etc.</code></pre>
- Nhập nội dung <code>192.168.1.99 local.kotanglish</code> vào cuối file host và lưu lại.
<i><b>Lưu ý:</b> Thay đổi dịa chỉ ip <code>192.168.1.99</code> bằng địa chỉ ip server của bạn</i>
<p>Lúc này từ trình duyệt ở máy host, có thể truy cập đến Webserver máy ảo bằng địa chỉ <code>local.kotanglish</code>.</p>

