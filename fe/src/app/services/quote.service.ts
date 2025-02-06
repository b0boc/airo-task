import { HttpClient, HttpErrorResponse } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable} from 'rxjs';
import { catchError } from 'rxjs/operators';
import { AlertsService } from './alerts.service';

@Injectable({
  providedIn: 'root'
})
export class QuoteService {
  private URL: string = 'http://localhost:8000/api';

  constructor(private http: HttpClient, private alert: AlertsService) { }

  getQuote(req: any): Observable<any> {
    return this.http.post(`${this.URL}/quotation`, req)
      .pipe(
        catchError((error: HttpErrorResponse) => this.alert.handleError(error))
      );
  }
}
