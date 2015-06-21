# readme first
0. git init //tại folder cần làm việc với git

1.  git config --global user.name "your user name" //khai báo kết nối
	git config --global user.email "your email"

2.  git status //xem thay đổi file, thư mục

3. git add tên_file,tên_folder_cần_add //mỗi cái 1 dòng

4.  git commit -m "chú thích commit gì"

5.  git push -u origin master


//them một cái remote,lần đầu thì đặt tên là origin (thực hiện sau bước 4)
    git remote add origin tên_repositories_trên_github //vd:git@github.com:tên_user/tên_repositories.git

//clone:

	git clone git@github.com:tên_user/tên_repositories.git

//git clone:

Lệnh này sẽ sao chép toàn bộ dữ liệu trên repository và sao chép luôn các thiết lập về repository, tức là nó sẽ tự động tạo một master branch trên máy tính của bạn. Lệnh này chỉ nên sử dụng khi bạn cần tạo mới một Git mới trên máy tính với toàn bộ dữ liệu và thiết lập của một remote repository.

//git pull:

Lệnh này sẽ tự động lấy toàn bộ dữ liệu từ remote repository và gộp vào cái branch hiện tại bạn đang làm việc.

//git fetch:

Lệnh này sẽ lấy toàn bộ dữ liệu từ remote repository nhưng sẽ cho phép bạn gộp thủ công vào một branch nào đó trên thư mục Git ở máy tính.