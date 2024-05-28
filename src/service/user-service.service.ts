import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class UserServiceService {

  private apiUrl = 'http://localhost/reg-login-api/';

  constructor(private http: HttpClient) { }

  addUser(user: any): Observable<any> {
    return this.http.post<any>(this.apiUrl + 'registration.php', user);
  }

  loginUser(user: any): Observable<any> {
    return this.http.post<any>(this.apiUrl + 'login.php', user);
  }
}
