# PRD — QuickBite Food Delivery & Ordering Platform

---

## 1. Product Overview

**Product Name:** QuickBite

**Vision:** Build a scalable, multi-region food ordering and delivery platform connecting customers, restaurants, delivery agents, and admins — with strong consistency for money and orders, and clear operational ownership across all parties.

---

## 2. User Types & Roles

### 2.1 Customer
- Browse restaurants and view menus
- Place orders and choose payment method (Online / Cash on Delivery)
- Track order and delivery status in real time
- View full order history

### 2.2 Restaurant (RBAC Required)

RBAC is enforced **per restaurant**, not globally. Each restaurant has three internal roles:

| Role | Permissions |
|---|---|
| **Owner** | Manage restaurant profile, menu & pricing, view all orders, view financial balance & payout history, assign/revoke staff roles |
| **Manager** | Manage menu, accept/reject orders, update order status, view orders & basic analytics |
| **Staff (Cashier)** | View incoming orders, update preparation status only |

### 2.3 Delivery Agent
- Accept or reject delivery tasks
- Pick up orders from restaurants
- Update delivery status: `Assigned` → `Picked Up` → `Delivered`
- View delivery history and earnings (read-only)

### 2.4 Admin
- Manage restaurants and delivery agents
- View all orders across the platform
- Monitor payments and record restaurant payouts

---

## 3. Order Lifecycle

```
Customer places order
       ↓
Payment decision
  ├─ Online → payment authorization required first
  └─ COD    → no pre-payment, proceed directly
       ↓
Order sent to restaurant
       ↓
Restaurant accepts or rejects
       ↓
Order prepared
       ↓
Delivery agent assigned (auto, proximity-based)
       ↓
Order picked up
       ↓
Order delivered
       ↓
Financial settlement recorded
```

---

## 4. Delivery Assignment

**Default: Automatic Assignment**
- Based on proximity and agent availability

**Manual Override (Restaurant)**
- Reassign agent
- Handle edge cases

**Agent Actions**
- Accept delivery task
- Reject delivery task (limited retries allowed)

---

## 5. Payments & Money Flow

### Payment Methods
- Online payment (card / gateway)
- Cash on Delivery (COD)

### Online Payment Flow
1. Payment is authorized before order confirmation
2. Order is only created after confirmed payment
3. Payment handling is idempotent (safe to retry without double-charge)

### COD Flow
1. Order is created without pre-payment
2. Delivery agent collects cash on delivery

### Restaurant Balance Model
- Each restaurant maintains a running balance
- Balance increases when an order is delivered successfully
- Platform commission is deducted automatically at settlement
- Restaurants are paid externally (bank transfer)
- Admin records each payout event in the system
- Balance is reduced after the payout is recorded
- Full payout history is retained indefinitely

---

## 6. Functional Requirements

### Customer
- Account registration and management
- Restaurant discovery and browsing
- Menu browsing and filtering
- Cart management
- Order placement with payment selection
- Real-time order tracking

### Restaurant
- Menu CRUD (items, prices, availability)
- Order management (accept / reject / status updates)
- Financial balance view
- Staff role management (Owner only)

### Delivery Agent
- View and respond to task assignments
- Update delivery status at each step
- View delivery history

### Admin
- Full visibility across all orders and restaurants
- Manual overrides on orders and assignments
- Financial reconciliation and monitoring
- Payout recording
- Reporting

---

## 7. Non-Functional Requirements

| Requirement | Target |
|---|---|
| **Availability** | High — order placement and payments are critical paths |
| **Latency** | Browsing < 1s; Checkout < 1–3s |
| **Consistency** | Strong for orders and payments; eventual for analytics |
| **Scalability** | Must handle meal-time traffic spikes (10× average) |
| **Security** | Strong authorization boundaries; secure payment handling |
| **Multi-Region** | Active traffic routing to nearest region; regional reads; strong consistency where required |

---

## 8. Capacity & Scale (Back-of-the-Envelope)

### Traffic Estimates

| Metric | Value |
|---|---|
| Monthly Active Users | 1,000,000 |
| Daily Active Users | 200,000 |
| Actions per user per day | ~27 |
| Requests per day | ~5.4M |
| Average RPS | ~62 |
| Peak RPS | ~620 |

### Read / Write Split

| Type | Ratio |
|---|---|
| Reads | 85% |
| Writes | 15% |

### Storage & Bandwidth

| Metric | Estimate |
|---|---|
| Orders table growth | ~146 GB / year |
| Daily bandwidth | ~270 GB / day |

---

## 9. Data Retention

| Data Type | Retention Policy |
|---|---|
| Orders | Long-term; app surfaces current-year only. Prior years archived to cold storage. |
| Payments | Same policy as orders |
| Logs | 30-day rolling window |
| Analytics | Aggregated and archived; raw events time-limited |

---

## 10. Multi-Region Assumptions

- Users are served from the nearest available region
- Restaurants are tied to a primary region
- Orders are processed in the restaurant's region
- Financial data requires strong consistency (no eventual consistency)

---

## 11. Constraints & Risks

| Risk | Description |
|---|---|
| Meal-time spikes | Traffic peaks 10× at lunch and dinner hours |
| Payment provider failures | Requires retry logic and idempotency keys |
| Delivery agent shortages | Need fallback / manual assignment paths |
| Cross-region consistency | Complex to guarantee strong consistency across regions |
| Settlement reconciliation errors | Financial data must be auditable and replay-safe |

---

## 12. Success Criteria

- Orders complete end-to-end without data loss
- Payments are correct, idempotent, and fully auditable
- Restaurant balances are accurate after every transaction
- System remains stable under peak load
- Authorization boundaries are clear and consistently enforced

---

## 13. Out of Scope

- Recommendation / personalization engine
- Loyalty programs and rewards
- AI-based delivery route optimization
- Customer reviews and ratings
