PGDMP                       }           postgres    17.4    17.4 7               0    0    ENCODING    ENCODING        SET client_encoding = 'UTF8';
                           false            	           0    0 
   STDSTRINGS 
   STDSTRINGS     (   SET standard_conforming_strings = 'on';
                           false            
           0    0 
   SEARCHPATH 
   SEARCHPATH     8   SELECT pg_catalog.set_config('search_path', '', false);
                           false                       1262    5    postgres    DATABASE     n   CREATE DATABASE postgres WITH TEMPLATE = template0 ENCODING = 'UTF8' LOCALE_PROVIDER = libc LOCALE = 'it-IT';
    DROP DATABASE postgres;
                     postgres    false                       0    0    DATABASE postgres    COMMENT     N   COMMENT ON DATABASE postgres IS 'default administrative connection database';
                        postgres    false    4875            �            1255    33126    update_snip_likes()    FUNCTION       CREATE FUNCTION public.update_snip_likes() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
BEGIN
    UPDATE snips
    SET challenge_points = (
        SELECT COUNT(*) FROM user_snip_likes WHERE snip_id = NEW.snip_id
    )
    WHERE id = NEW.snip_id;
    RETURN NULL;
END;
$$;
 *   DROP FUNCTION public.update_snip_likes();
       public               postgres    false            �            1259    33127 
   challenges    TABLE     �   CREATE TABLE public.challenges (
    name text NOT NULL,
    description text,
    date_start timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    date_end timestamp without time zone,
    winners text[],
    image text,
    type text
);
    DROP TABLE public.challenges;
       public         heap r       postgres    false            �            1259    33133    comments    TABLE     
  CREATE TABLE public.comments (
    id integer NOT NULL,
    creator character varying(16),
    post_name character varying(16),
    content text NOT NULL,
    created_at timestamp without time zone DEFAULT now(),
    child_of integer,
    likes integer DEFAULT 0
);
    DROP TABLE public.comments;
       public         heap r       postgres    false            �            1259    33140    comments_id_seq    SEQUENCE     �   CREATE SEQUENCE public.comments_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 &   DROP SEQUENCE public.comments_id_seq;
       public               postgres    false    218                       0    0    comments_id_seq    SEQUENCE OWNED BY     C   ALTER SEQUENCE public.comments_id_seq OWNED BY public.comments.id;
          public               postgres    false    219            �            1259    33141    drafts    TABLE     ,  CREATE TABLE public.drafts (
    id integer NOT NULL,
    creator text,
    created_at timestamp without time zone DEFAULT now() NOT NULL,
    type character varying(32),
    description text,
    tags text[],
    file_location character varying(255),
    variation_of text,
    challenge_of text
);
    DROP TABLE public.drafts;
       public         heap r       postgres    false            �            1259    33147    drafts_id_seq    SEQUENCE     �   CREATE SEQUENCE public.drafts_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 $   DROP SEQUENCE public.drafts_id_seq;
       public               postgres    false    220                       0    0    drafts_id_seq    SEQUENCE OWNED BY     ?   ALTER SEQUENCE public.drafts_id_seq OWNED BY public.drafts.id;
          public               postgres    false    221            �            1259    33148    snips    TABLE     �  CREATE TABLE public.snips (
    id integer NOT NULL,
    creator text,
    views integer DEFAULT 0,
    created_at timestamp without time zone DEFAULT now(),
    description text,
    element_type character varying(32),
    tags text[],
    file_location character varying(255) NOT NULL,
    likes integer DEFAULT 0,
    saved integer DEFAULT 0,
    variation_of text,
    challenge_of text
);
    DROP TABLE public.snips;
       public         heap r       postgres    false            �            1259    33157    snips_id_seq    SEQUENCE     �   CREATE SEQUENCE public.snips_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 #   DROP SEQUENCE public.snips_id_seq;
       public               postgres    false    222                       0    0    snips_id_seq    SEQUENCE OWNED BY     =   ALTER SEQUENCE public.snips_id_seq OWNED BY public.snips.id;
          public               postgres    false    223            �            1259    33158    snips_with_likes    VIEW     �  CREATE VIEW public.snips_with_likes AS
SELECT
    NULL::integer AS id,
    NULL::text AS creator,
    NULL::integer AS views,
    NULL::timestamp without time zone AS created_at,
    NULL::text AS description,
    NULL::character varying(32) AS element_type,
    NULL::text[] AS tags,
    NULL::character varying(255) AS file_location,
    NULL::integer AS likes,
    NULL::integer AS saved,
    NULL::text AS variation_of,
    NULL::text AS challenge_of,
    NULL::bigint AS challenge_likes;
 #   DROP VIEW public.snips_with_likes;
       public       v       postgres    false            �            1259    33162    user_snip_likes    TABLE     d   CREATE TABLE public.user_snip_likes (
    user_id integer NOT NULL,
    snip_id integer NOT NULL
);
 #   DROP TABLE public.user_snip_likes;
       public         heap r       postgres    false            �            1259    33165    users    TABLE     �  CREATE TABLE public.users (
    id integer NOT NULL,
    username character varying(50) NOT NULL,
    email character varying(100) NOT NULL,
    password character varying(255) NOT NULL,
    likedsnippets text[],
    savedsnippets text[],
    bio text DEFAULT 'This is my bio :)'::text,
    followers text[],
    following text[],
    remember_token text,
    likedcomments text[]
);
    DROP TABLE public.users;
       public         heap r       postgres    false            �            1259    33171    users_id_seq    SEQUENCE     �   CREATE SEQUENCE public.users_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 #   DROP SEQUENCE public.users_id_seq;
       public               postgres    false    226                       0    0    users_id_seq    SEQUENCE OWNED BY     =   ALTER SEQUENCE public.users_id_seq OWNED BY public.users.id;
          public               postgres    false    227            �            1259    33172    users_with_likes    VIEW     	  CREATE VIEW public.users_with_likes AS
 SELECT u.id,
    u.username,
    u.email,
    u.password,
    u.likedsnippets,
    u.savedsnippets,
    u.bio,
    u.followers,
    u.following,
    u.remember_token,
    COALESCE(sum(s.challenge_likes), (0)::numeric) AS user_challenge_likes
   FROM (public.users u
     LEFT JOIN public.snips_with_likes s ON (((u.username)::text = s.creator)))
  GROUP BY u.id, u.username, u.email, u.password, u.likedsnippets, u.savedsnippets, u.bio, u.followers, u.following, u.remember_token;
 #   DROP VIEW public.users_with_likes;
       public       v       postgres    false    226    226    226    226    226    226    226    226    226    226    224    224            B           2604    33177    comments id    DEFAULT     j   ALTER TABLE ONLY public.comments ALTER COLUMN id SET DEFAULT nextval('public.comments_id_seq'::regclass);
 :   ALTER TABLE public.comments ALTER COLUMN id DROP DEFAULT;
       public               postgres    false    219    218            E           2604    33178 	   drafts id    DEFAULT     f   ALTER TABLE ONLY public.drafts ALTER COLUMN id SET DEFAULT nextval('public.drafts_id_seq'::regclass);
 8   ALTER TABLE public.drafts ALTER COLUMN id DROP DEFAULT;
       public               postgres    false    221    220            G           2604    33179    snips id    DEFAULT     d   ALTER TABLE ONLY public.snips ALTER COLUMN id SET DEFAULT nextval('public.snips_id_seq'::regclass);
 7   ALTER TABLE public.snips ALTER COLUMN id DROP DEFAULT;
       public               postgres    false    223    222            L           2604    33180    users id    DEFAULT     d   ALTER TABLE ONLY public.users ALTER COLUMN id SET DEFAULT nextval('public.users_id_seq'::regclass);
 7   ALTER TABLE public.users ALTER COLUMN id DROP DEFAULT;
       public               postgres    false    227    226            �          0    33127 
   challenges 
   TABLE DATA           c   COPY public.challenges (name, description, date_start, date_end, winners, image, type) FROM stdin;
    public               postgres    false    217   �J       �          0    33133    comments 
   TABLE DATA           `   COPY public.comments (id, creator, post_name, content, created_at, child_of, likes) FROM stdin;
    public               postgres    false    218   �K       �          0    33141    drafts 
   TABLE DATA           }   COPY public.drafts (id, creator, created_at, type, description, tags, file_location, variation_of, challenge_of) FROM stdin;
    public               postgres    false    220   8L                 0    33148    snips 
   TABLE DATA           �   COPY public.snips (id, creator, views, created_at, description, element_type, tags, file_location, likes, saved, variation_of, challenge_of) FROM stdin;
    public               postgres    false    222   �L                 0    33162    user_snip_likes 
   TABLE DATA           ;   COPY public.user_snip_likes (user_id, snip_id) FROM stdin;
    public               postgres    false    225   @Q                 0    33165    users 
   TABLE DATA           �   COPY public.users (id, username, email, password, likedsnippets, savedsnippets, bio, followers, following, remember_token, likedcomments) FROM stdin;
    public               postgres    false    226   tQ                  0    0    comments_id_seq    SEQUENCE SET     >   SELECT pg_catalog.setval('public.comments_id_seq', 18, true);
          public               postgres    false    219                       0    0    drafts_id_seq    SEQUENCE SET     <   SELECT pg_catalog.setval('public.drafts_id_seq', 24, true);
          public               postgres    false    221                       0    0    snips_id_seq    SEQUENCE SET     ;   SELECT pg_catalog.setval('public.snips_id_seq', 52, true);
          public               postgres    false    223                       0    0    users_id_seq    SEQUENCE SET     :   SELECT pg_catalog.setval('public.users_id_seq', 7, true);
          public               postgres    false    227            O           2606    33182    challenges challenges_pkey 
   CONSTRAINT     Z   ALTER TABLE ONLY public.challenges
    ADD CONSTRAINT challenges_pkey PRIMARY KEY (name);
 D   ALTER TABLE ONLY public.challenges DROP CONSTRAINT challenges_pkey;
       public                 postgres    false    217            Q           2606    33184    comments comments_pkey 
   CONSTRAINT     T   ALTER TABLE ONLY public.comments
    ADD CONSTRAINT comments_pkey PRIMARY KEY (id);
 @   ALTER TABLE ONLY public.comments DROP CONSTRAINT comments_pkey;
       public                 postgres    false    218            S           2606    33186    drafts drafts_pkey 
   CONSTRAINT     P   ALTER TABLE ONLY public.drafts
    ADD CONSTRAINT drafts_pkey PRIMARY KEY (id);
 <   ALTER TABLE ONLY public.drafts DROP CONSTRAINT drafts_pkey;
       public                 postgres    false    220            U           2606    33188    snips snips_pkey 
   CONSTRAINT     N   ALTER TABLE ONLY public.snips
    ADD CONSTRAINT snips_pkey PRIMARY KEY (id);
 :   ALTER TABLE ONLY public.snips DROP CONSTRAINT snips_pkey;
       public                 postgres    false    222            W           2606    33190    snips unique_file_location 
   CONSTRAINT     ^   ALTER TABLE ONLY public.snips
    ADD CONSTRAINT unique_file_location UNIQUE (file_location);
 D   ALTER TABLE ONLY public.snips DROP CONSTRAINT unique_file_location;
       public                 postgres    false    222            Y           2606    33192 $   user_snip_likes user_snip_likes_pkey 
   CONSTRAINT     p   ALTER TABLE ONLY public.user_snip_likes
    ADD CONSTRAINT user_snip_likes_pkey PRIMARY KEY (user_id, snip_id);
 N   ALTER TABLE ONLY public.user_snip_likes DROP CONSTRAINT user_snip_likes_pkey;
       public                 postgres    false    225    225            [           2606    33194    users users_email_key 
   CONSTRAINT     Q   ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_email_key UNIQUE (email);
 ?   ALTER TABLE ONLY public.users DROP CONSTRAINT users_email_key;
       public                 postgres    false    226            ]           2606    33196    users users_pkey 
   CONSTRAINT     N   ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);
 :   ALTER TABLE ONLY public.users DROP CONSTRAINT users_pkey;
       public                 postgres    false    226            _           2606    33198    users users_username_key 
   CONSTRAINT     W   ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_username_key UNIQUE (username);
 B   ALTER TABLE ONLY public.users DROP CONSTRAINT users_username_key;
       public                 postgres    false    226            �           2618    33161    snips_with_likes _RETURN    RULE     �  CREATE OR REPLACE VIEW public.snips_with_likes AS
 SELECT s.id,
    s.creator,
    s.views,
    s.created_at,
    s.description,
    s.element_type,
    s.tags,
    s.file_location,
    s.likes,
    s.saved,
    s.variation_of,
    s.challenge_of,
    count(usl.snip_id) AS challenge_likes
   FROM (public.snips s
     LEFT JOIN public.user_snip_likes usl ON ((s.id = usl.snip_id)))
  GROUP BY s.id;
 �  CREATE OR REPLACE VIEW public.snips_with_likes AS
SELECT
    NULL::integer AS id,
    NULL::text AS creator,
    NULL::integer AS views,
    NULL::timestamp without time zone AS created_at,
    NULL::text AS description,
    NULL::character varying(32) AS element_type,
    NULL::text[] AS tags,
    NULL::character varying(255) AS file_location,
    NULL::integer AS likes,
    NULL::integer AS saved,
    NULL::text AS variation_of,
    NULL::text AS challenge_of,
    NULL::bigint AS challenge_likes;
       public               postgres    false    222    222    222    222    222    222    222    222    222    222    222    222    4693    225    224            `           2606    33200    comments comments_child_of_fkey    FK CONSTRAINT     �   ALTER TABLE ONLY public.comments
    ADD CONSTRAINT comments_child_of_fkey FOREIGN KEY (child_of) REFERENCES public.comments(id);
 I   ALTER TABLE ONLY public.comments DROP CONSTRAINT comments_child_of_fkey;
       public               postgres    false    4689    218    218            c           2606    33205    drafts drafts_challange_of_fkey    FK CONSTRAINT     �   ALTER TABLE ONLY public.drafts
    ADD CONSTRAINT drafts_challange_of_fkey FOREIGN KEY (challenge_of) REFERENCES public.challenges(name);
 I   ALTER TABLE ONLY public.drafts DROP CONSTRAINT drafts_challange_of_fkey;
       public               postgres    false    4687    220    217            d           2606    33210    drafts drafts_creator_fkey    FK CONSTRAINT     �   ALTER TABLE ONLY public.drafts
    ADD CONSTRAINT drafts_creator_fkey FOREIGN KEY (creator) REFERENCES public.users(username) ON UPDATE CASCADE;
 D   ALTER TABLE ONLY public.drafts DROP CONSTRAINT drafts_creator_fkey;
       public               postgres    false    220    226    4703            a           2606    33215    comments fk_post_name    FK CONSTRAINT     �   ALTER TABLE ONLY public.comments
    ADD CONSTRAINT fk_post_name FOREIGN KEY (post_name) REFERENCES public.snips(file_location);
 ?   ALTER TABLE ONLY public.comments DROP CONSTRAINT fk_post_name;
       public               postgres    false    4695    222    218            b           2606    33220    comments fk_user_name    FK CONSTRAINT     �   ALTER TABLE ONLY public.comments
    ADD CONSTRAINT fk_user_name FOREIGN KEY (creator) REFERENCES public.users(username) ON UPDATE CASCADE;
 ?   ALTER TABLE ONLY public.comments DROP CONSTRAINT fk_user_name;
       public               postgres    false    4703    226    218            e           2606    33225    snips snips_creator_fkey    FK CONSTRAINT     �   ALTER TABLE ONLY public.snips
    ADD CONSTRAINT snips_creator_fkey FOREIGN KEY (creator) REFERENCES public.users(username) ON UPDATE CASCADE;
 B   ALTER TABLE ONLY public.snips DROP CONSTRAINT snips_creator_fkey;
       public               postgres    false    226    222    4703            g           2606    33230 ,   user_snip_likes user_snip_likes_snip_id_fkey    FK CONSTRAINT     �   ALTER TABLE ONLY public.user_snip_likes
    ADD CONSTRAINT user_snip_likes_snip_id_fkey FOREIGN KEY (snip_id) REFERENCES public.snips(id) ON DELETE CASCADE;
 V   ALTER TABLE ONLY public.user_snip_likes DROP CONSTRAINT user_snip_likes_snip_id_fkey;
       public               postgres    false    222    4693    225            h           2606    33235 ,   user_snip_likes user_snip_likes_user_id_fkey    FK CONSTRAINT     �   ALTER TABLE ONLY public.user_snip_likes
    ADD CONSTRAINT user_snip_likes_user_id_fkey FOREIGN KEY (user_id) REFERENCES public.users(id) ON DELETE CASCADE;
 V   ALTER TABLE ONLY public.user_snip_likes DROP CONSTRAINT user_snip_likes_user_id_fkey;
       public               postgres    false    4701    226    225            f           2606    33240    snips vincolo_challenge    FK CONSTRAINT     �   ALTER TABLE ONLY public.snips
    ADD CONSTRAINT vincolo_challenge FOREIGN KEY (challenge_of) REFERENCES public.challenges(name);
 A   ALTER TABLE ONLY public.snips DROP CONSTRAINT vincolo_challenge;
       public               postgres    false    217    222    4687            �     x����N�0�5|����YJ\b&�%�)�R�����e&����wqO�ƘK<�^�+�zm: Y���*�rI�����!W9|ʡ�iEhI%�	sH�K�U�:�yl�x;�{ ?<x찻s}޳`��sQ�lqc�L�Ȋ�Ϭ�e�J�#+�ﵰ.;�Rl���X��ioW˪Y�-U0Q�R���5v��زh{K�&����u�I�L��i����7,6^Q֥�~���f���Q��f���<�-��)n��50�ĵm��=R�      �   ~   x�=�K�  ��p�ٹ*a�rO��DI`��[Ml�֏��Ú*)��T�ʊ�:~�K����KXv�B�I�I	���W��j  F���K�v�5���8yCҞ�KǅVF�|?��3�>�*�      �   c   x��K@0 ���� a�����"#>m	#~qw^� �	`�D ��q^PRn4��"���6�w��|h���</t�9v֝��f���_աR��          �  x��Uko�8���
�;e�୪R�v��6m�U�R�ʀ��M��������4Q�ι��s�9j�JT���1��7� 3�P���"��ݖ��p��r�e%k�-J{k�F?�(C~���B�B�����Q�*y�H���?B��(�HB���q�d�;Y7�ĕ���]gpjF�S�G�ӷs+;�|V����Q����b��rݵe�`��^c�.��/^����,h�b7&~D_-�a�=����~�_�		]΢8�^+q�)S�����s#��Re�Xnj۞��h���w��S�xq�G.��A�L#�Ye:�s�e�j�2�jae�:�:���l���Zy���{4<n�!�[c'*߿L,��	/�<�}7��{ml)[�m��J���_^�Q@b��FW
�9߄~��o�ڌ�=����9++�ι2�ڣ�u�U�&}� ��|NT�cBƦP��b�Ei�7"M%�J��
D,PeD.[P[e�{g�윿���W��s�r
�Y���Rzq�v��s�.�FOma�4�0�K)�@�i�=��횬.���|�`�!�O+�~�h�.	}��$�(��/�b���)����<�ٹ�H�J���>Zj<�9�g����ɽ���D��1�K�Y�6-)��������۷o~�y��X�t�3��܈�z���ڏb��l����OF`��id�AL9*e)��k+t!��$D�,׎����Q��-,vj4�"�by����1�3�8!�F>	�t��#�[���ZݙJ:7���NZ��:�"�=X)��>նG����;���zp�d�/j>�t�� �/�p+�n(�f-O-�-5�31��{a�:k��,�9f8n�*�W�v�� �mk���LA�=��Y�:�Z�L�OS�<?���l�h�tn��\.X�	����F<<H3��~���ZO��*uڟ����f�~���Y�1���Fg��fZZ#ϲ�)�T�xTa<��Q�<>�
�pӚD<.j��$�(���s�R:�_�RUpY�g����0�)�R��I��%��Y¾*�g��5c^ gϖ�-���X�A�J\	\�\�o�l��`��k�{�Մ�,$]�@~�@0x0���ܘ������ZQk=�,���T�7��_��ݽ���ͨ�         $   x�3�45�2�45�2�!,cS��� �b���� q,j         I  x���[��@ ���W�þ41��>U]u���(�;0� �7���j���m���L&�ɜ�ɈL�`�B&A"q]�}	0@	��<����?6�t�]s��-��r=N��,9N/�CV�a�#����F��9RXP����6D��Y�}py���y�~��g��Qz#��LvӸ�*R����#�w �����ʓ�}S�i�4Z<ob~N2�Ι��{�+����\6�ӽ�2Z��Ku�U����`җE�xj�Zn4ul�b%[c��B�V��c�5����&*�#��@�zQM=� �����hCo���5J=R��'$�y��(����}�v�v���#]��U����b���<�9���-iX������xķr0{ʣ����K���H�����˿�zp�~8�{��}��������B��,g�lg���v���m5u$��L�4s/��CCf�1HK���gD��C`�Ѝ�m�}��۩է�w��iٶ���gSnt�',�[��^Sh�z�1�z7M��p"ΓQ�e���j/pd�p�l�X���\�����4�[���+�:ol�����V�     