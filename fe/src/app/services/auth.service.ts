import { HttpClient, HttpErrorResponse } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { catchError, } from 'rxjs';
import { AlertsService } from './alerts.service';

@Injectable({
  providedIn: 'root'
})
export class AuthService {

  private URL: string = 'http://localhost:8000/api';

  constructor(private http: HttpClient, private alert: AlertsService) { }

  login(credentials: { email: string, password: string }) {
    return this.http.post(`${this.URL}/login`, credentials)
      .pipe(
        catchError((error: HttpErrorResponse) => this.alert.handleError(error))
      );
  }

  logout() {
    return this.http.get(`${this.URL}/logout`)
      .pipe(
        catchError((error: HttpErrorResponse) => this.alert.handleError(error))
      );
  }

}
